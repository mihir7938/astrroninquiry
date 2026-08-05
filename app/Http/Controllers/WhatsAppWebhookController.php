<?php

namespace App\Http\Controllers;

use App\Models\WhatsappMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class WhatsAppWebhookController extends Controller
{
    /**
     * Meta Verification
     */
    public function verify(Request $request)
    {
        $verifyToken = env('WHATSAPP_WEBHOOK_VERIFY_TOKEN');
        $mode = $request->query('hub_mode', $request->query('hub.mode'));
        $token = $request->query('hub_verify_token', $request->query('hub.verify_token'));
        $challenge = $request->query('hub_challenge', $request->query('hub.challenge'));
        if ($mode === 'subscribe' && $token === $verifyToken) {
            return response($challenge, 200)
                ->header('Content-Type', 'text/plain');
        }
        return response('Invalid Verify Token', 403);
    }

    /**
     * Receive WhatsApp Events
     */
    public function receive(Request $request)
    {
        Log::info('Full payload', $request->all());
        $entry = $request->input('entry.0.changes.0.value');
        if (isset($entry['messages'])) {
            $message = $entry['messages'][0];
            $contact = $entry['contacts'][0] ?? [];
            WhatsappMessage::create([
                'message_id'=>$message['id'],
                'wa_id'       => $contact['wa_id'] ?? $message['from'],
                'from_number' => $message['from'],
                'to_number'   => $entry['metadata']['display_phone_number'],
                'direction'=>'incoming',
                'type'=>$message['type'],
                'message'=>$message['text']['body'] ?? '',
                'status'=>'received',
                'payload'=>$message
            ]);
        }
        if (isset($entry['statuses'])) {
            $status = $entry['statuses'][0];
            WhatsappMessage::where('message_id',$status['id'])
            ->update([
                'status'=>$status['status'],
                'payload'=>$status
            ]);
        }
        return response('EVENT_RECEIVED', 200);
    }
}
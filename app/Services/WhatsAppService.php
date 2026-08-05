<?php

namespace App\Services;

use App\Models\WhatsappMessage;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsappService
{
    protected $token;
    protected $phoneNumberId;
    protected $version;
    protected $baseUrl;

    public function __construct()
    {
        $this->token = config('services.whatsapp.token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->version = config('services.whatsapp.version');
        $this->baseUrl = "https://graph.facebook.com/{$this->version}/{$this->phoneNumberId}/messages";
    }
    /**
     * Common HTTP Request
     */
    protected function send(array $payload)
    {
        try {

            $response = Http::withToken($this->token)
                ->acceptJson()
                ->post($this->baseUrl, $payload);

            $result = $response->json();
            if ($response->successful()) {
                WhatsappMessage::create([
                    'message_id' => $result['messages'][0]['id'] ?? null,
                    'wa_id'        => $payload['to'],
                    'from_number'  => config('services.whatsapp.whatsapp_number'),
                    'to_number'    => $payload['to'],
                    'direction' => 'outgoing',
                    'type' => $payload['type'],
                    'message' => $payload['type']=='text'
                        ? ($payload['text']['body'] ?? '')
                        : ($payload['template']['name'] ?? ''),
                    'status' => 'sent',
                    'payload' => $result
                ]);
            }

            return [
                'success' => $response->successful(),
                'status' => $response->status(),
                'data' => $result
            ];

        } catch (\Exception $e) {

            Log::error('WhatsApp Exception', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'status' => 500,
                'message' => $e->getMessage()
            ];
        }
    }
    /**
     * Send Text Message
     */
    public function sendText($mobile, $message)
    {
        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $mobile,
            "type" => "text",
            "text" => [
                "preview_url" => false,
                "body" => $message
            ]
        ];

        return $this->send($payload);
    }
    public function sendTemplate($mobile, $templateName, $parameters = [], $language = 'en')
    {
        $bodyParameters = [];

        foreach ($parameters as $name => $value) {
            $bodyParameters[] = [
                "type" => "text",
                "parameter_name" => $name,
                "text" => (string) $value
            ];
        }

        $payload = [
            "messaging_product" => "whatsapp",
            "to" => $mobile,
            "type" => "template",
            "template" => [
                "name" => $templateName,
                "language" => [
                    "code" => $language
                ]
            ]
        ];

        // Add body parameters only if provided
        if (!empty($bodyParameters)) {
            $payload['template']['components'] = [
                [
                    "type" => "body",
                    "parameters" => $bodyParameters
                ]
            ];
        }

        return $this->send($payload);
    }
    public function sendInquiryReply($mobile, $customerName, $inquiryNo, $contactMobile, $contactEmail, $companyName)
    {
        return $this->sendTemplate(
            $mobile,
            'inquiry_reply',
            [
                'customer_name' => $customerName,
                'inquiry_no' => $inquiryNo,
                'contact_number' => $contactMobile,
                'contact_email' => $contactEmail,
                'company_name' => $companyName,
            ]
        );
    }   
    public function sendInquiryAssigned($mobile, $assignedPerson, $inquiryNo, $customerName, $customerMobile)
    {
        return $this->sendTemplate(
            $mobile,
            'complaint_assigned',
            [
                'name' => $assignedPerson,
                'complain_no' => $inquiryNo,
                'customer_name' => $customerName,
                'customer_mobile' => $customerMobile,
            ]
        );
    }
}

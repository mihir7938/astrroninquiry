<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UploadImageService;
use App\Services\EmailService;
use App\Services\CityService;
use App\Services\BusinessService;
use App\Services\ProductService;
use App\Services\StatusService;
use App\Services\AssignService;
use App\Services\UserService;
use App\Services\InquiryService;
use App\Services\InquiryPhotosService;
use App\Services\InquiryProductService;
use App\Models\InquiryProduct;
use App\Models\InquiryPhoto;
use App\Models\WhatsappMessage;
use App\Jobs\SendWhatsappMessageJob;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    private $imageService, $emailService, $cityService, $businessService, $productService, $statusService, $assignService, $userService, $inquiryService, $inquiryPhotosService, $inquiryProductService;

    public function __construct (
        UploadImageService $imageService,
        EmailService $emailService,
        CityService $cityService,
        BusinessService $businessService,
        ProductService $productService,
        StatusService $statusService,
        AssignService $assignService,
        UserService $userService,
        InquiryService $inquiryService,
        InquiryPhotosService $inquiryPhotosService,
        InquiryProductService $inquiryProductService
    )
    {
        $this->imageService = $imageService;
        $this->emailService = $emailService;
        $this->cityService = $cityService;
        $this->businessService = $businessService;
        $this->productService = $productService;
        $this->statusService = $statusService;
        $this->assignService = $assignService;
        $this->userService = $userService;
        $this->inquiryService = $inquiryService;
        $this->inquiryPhotosService = $inquiryPhotosService;
        $this->inquiryProductService = $inquiryProductService;
    }

    public function index(Request $request)
    {
        $user_id = Auth::user()->id;
    	$total_inquiry = $this->inquiryService->getTotalInquiriesByUser($user_id);
        $statuses = $this->statusService->getAllStatusByUserAssign($user_id);
        $assign_in_inquiry = $this->inquiryService->getTotalInquiriesByAssign($user_id, 'In');
        $assign_out_inquiry = $this->inquiryService->getTotalInquiriesByAssign($user_id, 'Out');
        return view('users.index')->with('total_inquiry', $total_inquiry)->with('statuses', $statuses)->with('assign_in_inquiry', $assign_in_inquiry)->with('assign_out_inquiry', $assign_out_inquiry);
    }
    public function addInquiry(Request $request)
    {
        $businesses = $this->businessService->getAllBusiness();
        $products = $this->productService->getAllProducts();
        $statuses = $this->statusService->getAllStatus();
        $users = $this->userService->getAllUsers();
        return view('users.add')->with('businesses', $businesses)->with('products', $products)->with('statuses', $statuses)->with('users', $users);
    }
    public function saveInquiry(Request $request)
    {
        $data['user_id'] = Auth::user()->id;
        $data['assign_id'] = $request->assign;
        $data['company_name'] = $request->company_name;
        $data['contact_person'] = $request->contact_person;
        $data['phone'] = $request->phone;
        $data['email'] = $request->email;
        $data['city'] = $request->city;
        $data['business_id'] = $request->business;
        $data['status_id'] = $request->status;
        $data['reff'] = $request->reff;
        $data['remarks'] = $request->remarks;
        $data['inquiry_date'] = date('Y-m-d');
        if ($request->hasFile('requirements')) {
            $requirements = [];
            foreach ($request->file('requirements') as $file) {
                $filename_req = $this->imageService->uploadFile($file, "assets/inquiry/requirements");
                $requirements[] = '/inquiry/requirements/' . $filename_req;
            }
            $data['requirements'] = implode(',', $requirements);
        }
        if ($request->hasFile('quotations')) {
            $quotations = [];
            foreach ($request->file('quotations') as $file) {
                $filename_quo = $this->imageService->uploadFile($file, "assets/inquiry/quotation");
                $quotations[] = '/inquiry/quotation/' . $filename_quo;
            }
            $data['quotation'] = implode(',', $quotations);
        }
        $inquiry_data = $this->inquiryService->create($data);
        $inquiry_id = $inquiry_data->id;
        if ($request->product) {
            foreach ($request->product as $key => $productId) {
                if (!$productId) {
                    continue;
                }
                $saveData = [
                    'inquiry_id' => $inquiry_id,
                    'product_id' => $productId,
                    'price' => $request->price[$key] ?? null,
                    'quantity' => $request->quantity[$key] ?? null,
                ];
                $newRow = InquiryProduct::create($saveData);
            }
        }
        if($request->has('image')){
            $data['inquiry_id'] = $inquiry_id;
            foreach($request->image as $img) {
                $filename = $this->imageService->uploadFile($img, "assets/inquiry");
                $data['image'] = '/inquiry/'.$filename;
                $this->inquiryPhotosService->create($data);
            }
        }
        $productNames = $inquiry_data->products()->with('product')->get()->pluck('product.name')->filter()->implode(', ');
        $message = WhatsappMessage::create([
            'wa_id'         => env('ADMIN_MOBILE'),
            'from_number'   => env('WHATSAPP_PHONE_NUMBER'),
            'to_number'     => env('ADMIN_MOBILE'),
            'direction'     => 'outgoing',
            'type'          => 'template',
            'template_name' => env('NEW_INQUIRY_TEMPLATE'),
            'parameters'    => [
                'staff_name' => Auth::user()->name,
                'inquiry_no'    => $inquiry_id,
                'customer_name'=> $request->contact_person,
                'customer_mobile' => $request->phone,
                'company_name'  => $request->company_name,
                'product'  => $productNames,
                'city'  => $request->city,
            ],
            'status'        => 'pending'
        ]);
        SendWhatsappMessageJob::dispatch($message->id);
        $assignedUser = $this->userService->getUserById($request->assign);
        $message2 = WhatsappMessage::create([
            'wa_id'         => '91' . $assignedUser->phone,
            'from_number'   => env('WHATSAPP_PHONE_NUMBER'),
            'to_number'     => '91' . $assignedUser->phone,
            'direction'     => 'outgoing',
            'type'          => 'template',
            'template_name' => env('ASSIGN_INQUIRY_TEMPLATE'),
            'parameters'    => [
                'staff_name' => $assignedUser->name,
                'customer_name'=> $request->contact_person,
                'company_name'  => $request->company_name,
                'customer_mobile' => $request->phone,
                'product'  => $productNames,
                'city'  => $request->city,
            ],
            'status'        => 'pending'
        ]);
        SendWhatsappMessageJob::dispatch($message2->id);
        $request->session()->put('message', 'inquiry has been generated successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('users.inquiries');
    }
    public function getInquiries(Request $request)
    {
        $user_id = Auth::user()->id;
        $status_id = "";
        $assign_type = "";
        $statuses = $this->statusService->getAllStatus();
        if( $request->has('status') ) {
            $status_id = $request->input('status');
        }
        if( $request->has('assign_type') ) {
            $assign_type = $request->input('assign_type');
        }
        $inquiries = $this->inquiryService->getInquiriesByUserAssign($user_id, $status_id, $assign_type);
        return view('users.inquiries')->with('statuses', $statuses)->with('status_id', $status_id)->with('assign_type', $assign_type)->with('inquiries', $inquiries);
    }
    public function fetchInquiriesByStatus(Request $request)
    {
        $user_id = Auth::user()->id;
        $inquiries = $this->inquiryService->getInquiriesByUserAssignByFilter($request, $user_id);
        return view('users.list')->with('inquiries', $inquiries)->render();
    }
    public function editInquiry(Request $request, $id)
    {
        try{
            $inquiry = $this->inquiryService->getInquiryById($id);
            if(!$inquiry){
                throw new BadRequestException('Invalid Request id');
            }
            $businesses = $this->businessService->getAllBusiness();
            $products = $this->productService->getAllProducts();
            $statuses = $this->statusService->getAllStatus();
            $users = $this->userService->getAllUsers();
            return view('users.edit')->with('inquiry', $inquiry)->with('businesses', $businesses)->with('products', $products)->with('statuses', $statuses)->with('users', $users);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('users.inquiries');
        }
    }
    public function updateInquiry(Request $request)
    {
        try{
            $inquiry = $this->inquiryService->getInquiryById($request->id);
            if(!$inquiry){
                throw new BadRequestException('Invalid Request id');
            }
            $oldAssignId = $inquiry->assign_id;
            if($request->assign) {
                $data['assign_id'] = $request->assign;
            }
            $data['contact_person'] = $request->contact_person;
            $data['phone'] = $request->phone;
            $data['email'] = $request->email;
            $data['city'] = $request->city;
            $data['business_id'] = $request->business;
            $data['status_id'] = $request->status;
            $data['reff'] = $request->reff;
            $data['remarks'] = $request->remarks;
            $data['followup_remarks_1'] = $request->followup_remarks_1;
            $data['followup_date_1'] = NULL;
            if($request->followup_date_1) {
                $data['followup_date_1'] = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_date_1)));
            }
            $data['followup_remarks_2'] = $request->followup_remarks_2;
            $data['followup_date_2'] = NULL;
            if($request->followup_date_2) {
                $data['followup_date_2'] = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_date_2)));
            }
            $data['followup_remarks_3'] = $request->followup_remarks_3;
            $data['followup_date_3'] = NULL;
            if($request->followup_date_3) {
                $data['followup_date_3'] = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_date_3)));
            }
            $data['followup_remarks_4'] = $request->followup_remarks_4;
            $data['followup_date_4'] = NULL;
            if($request->followup_date_4) {
                $data['followup_date_4'] = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_date_4)));
            }
            $data['followup_remarks_5'] = $request->followup_remarks_5;
            $data['followup_date_5'] = NULL;
            if($request->followup_date_5) {
                $data['followup_date_5'] = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_date_5)));
            }
            if ($request->hasFile('requirements')) {
                $newRequirements = [];
                foreach ($request->file('requirements') as $file) {
                    $filename_req = $this->imageService->uploadFile($file, "assets/inquiry/requirements");
                    $newRequirements[] = '/inquiry/requirements/' . $filename_req;
                }
                $oldRequirements = [];
                if ($inquiry->requirements) {
                    $oldRequirements = array_filter(
                        array_map('trim', explode(',', $inquiry->requirements))
                    );
                }
                $allRequirements = array_merge($oldRequirements, $newRequirements);
                $data['requirements'] = implode(',', $allRequirements);
            }
            if ($request->hasFile('quotations')) {
                $newQuotations = [];
                foreach ($request->file('quotations') as $file) {
                    $filename_quo = $this->imageService->uploadFile($file, "assets/inquiry/quotation");
                    $newQuotations[] = '/inquiry/quotation/' . $filename_quo;
                }
                $oldQuotations = [];
                if ($inquiry->quotation) {
                    $oldQuotations = array_filter(
                        array_map('trim', explode(',', $inquiry->quotation))
                    );
                }
                $allQuotations = array_merge($oldQuotations, $newQuotations);
                $data['quotation'] = implode(',', $allQuotations);
            }
            $this->inquiryService->update($inquiry, $data);
            $productExistingIds = [];
            if ($request->product) {
                foreach ($request->product as $key => $productId) {
                    if (!$productId) {
                        continue;
                    }
                    $saveData = [
                        'inquiry_id' => $inquiry->id,
                        'product_id' => $productId,
                        'price' => $request->price[$key] ?? null,
                        'quantity' => $request->quantity[$key] ?? null,
                    ];
                    $rowId = $request->row_id[$key] ?? null;
                    if ($rowId) {
                        $productRow = InquiryProduct::find($rowId);
                        if ($productRow) {
                            $productRow->update($saveData);
                            $productExistingIds[] = $productRow->id;
                        }
                    } else {
                        $newRow = InquiryProduct::create($saveData);
                        $productExistingIds[] = $newRow->id;
                    }
                }
            }
            InquiryProduct::where('inquiry_id', $inquiry->id)->whereNotIn('id', $productExistingIds)->delete();
            if($request->has('image')){
                $data['inquiry_id'] = $request->id;
                foreach($request->image as $img) {
                    $filename = $this->imageService->uploadFile($img, "assets/inquiry");
                    $data['image'] = '/inquiry/'.$filename;
                    $this->inquiryPhotosService->create($data);
                }
            }
            if ($oldAssignId != $request->assign) {
                $assignedUser = $this->userService->getUserById($request->assign);
                if ($assignedUser && $assignedUser->phone) {
                    $productNames = $inquiry->products()->with('product')->get()->pluck('product.name')->filter()->implode(', ');
                    $message = WhatsappMessage::create([
                        'wa_id'         => '91' . $assignedUser->phone,
                        'from_number'   => env('WHATSAPP_PHONE_NUMBER'),
                        'to_number'     => '91' . $assignedUser->phone,
                        'direction'     => 'outgoing',
                        'type'          => 'template',
                        'template_name' => env('ASSIGN_INQUIRY_TEMPLATE'),
                        'parameters'    => [
                            'staff_name' => $assignedUser->name,
                            'customer_name'=> $request->contact_person,
                            'company_name'  => $request->company_name,
                            'customer_mobile' => $request->phone,
                            'product'  => $productNames,
                            'city'  => $request->city,
                        ],
                        'status'        => 'pending'
                    ]);
                    SendWhatsappMessageJob::dispatch($message->id);
                }
            }
            $request->session()->put('message', 'inquiry has been updated successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('users.inquiries');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('users.inquiries');
        }
    }
    public function deleteImage(Request $request)
    {
        $photo = InquiryPhoto::find($request->id);
        if (!$photo) {
            return response()->json(['error' => 'Image not found'], 404);
        }
        $path = public_path('assets/' . $photo->image);
        if (file_exists($path)) {
            $this->imageService->deleteFile($path);
        }
        $photo->delete();
        return response()->json(['success' => true]);
    }
    public function deleteReqPDF(Request $request)
    {
        $inquiry = $this->inquiryService->getInquiryById($request->id);
        $deleteFile = $request->file;
        $requirements = $inquiry->requirements ? explode(',', $inquiry->requirements) : [];
        $remainingRequirements = [];
        foreach ($requirements as $requirement) {
            $requirement = trim($requirement);
            if ($requirement === $deleteFile) {
                $filepath = public_path('assets' . $requirement);
                if (file_exists($filepath)) {
                    $this->imageService->deleteFile($filepath);
                }
            } else {
                $remainingRequirements[] = $requirement;
            }
        }
        $data['requirements'] = !empty($remainingRequirements) ? implode(',', $remainingRequirements) : NULL;
        $this->inquiryService->update($inquiry, $data);
        return response()->json(['success' => true]);
    }
    public function deleteQuoPDF(Request $request)
    {
        $inquiry = $this->inquiryService->getInquiryById($request->id);
        $deleteFile = $request->file;
        $quotations = $inquiry->quotation ? explode(',', $inquiry->quotation) : [];
        $remainingQuotations = [];
        foreach ($quotations as $quotation) {
            $quotation = trim($quotation);
            if ($quotation === $deleteFile) {
                $filepath = public_path('assets' . $quotation);
                if (file_exists($filepath)) {
                    $this->imageService->deleteFile($filepath);
                }
            } else {
                $remainingQuotations[] = $quotation;
            }
        }
        $data['quotation'] = !empty($remainingQuotations) ? implode(',', $remainingQuotations) : NULL;
        $this->inquiryService->update($inquiry, $data);
        return response()->json(['success' => true]);
    }
    public function getAssignInquiries(Request $request)
    {
        $statuses = $this->statusService->getAllStatus();
        $assign_id = Auth::user()->id;
        $user_id = Auth::user()->id;
        $flag = 0;
        $inquiries = $this->inquiryService->getInquiriesByAssign($assign_id, $user_id);
        return view('users.assign')->with('statuses', $statuses)->with('inquiries', $inquiries)->with('flag', $flag);
    }
    public function fetchAssignInquiriesByStatus(Request $request)
    {
        $assign_id = Auth::user()->id;
        $flag = 0;
        $inquiries = $this->inquiryService->getInquiriesByAssignByStatus($request, $assign_id);
        return view('users.list')->with('inquiries', $inquiries)->with('flag', $flag)->render();
    }
    public function getTeam()
    {
        $user_id = Auth::user()->id;
        $users = $this->userService->getTeamByUser($user_id);
        return view('users.team')->with('users', $users);
    }
}
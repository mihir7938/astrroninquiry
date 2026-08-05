<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Services\UploadImageService;
use App\Services\CityService;
use App\Services\BusinessService;
use App\Services\ProductService;
use App\Services\StatusService;
use App\Services\AssignService;
use App\Services\UserService;
use App\Services\InquiryService;
use App\Services\InquiryPhotosService;
use App\Services\InquiryProductService;
use App\Services\WhatsappService;
use App\Models\Inquiry;
use App\Models\InquiryProduct;
use App\Models\InquiryPhoto;
use App\Models\User;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller {

	private $imageService, $cityService, $businessService, $productService, $statusService, $assignService, $userService, $inquiryService, $inquiryPhotosService, $inquiryProductService, $whatsappService;

    public function __construct(
        UploadImageService $imageService,
        CityService $cityService,
        BusinessService $businessService,
        ProductService $productService,
        StatusService $statusService,
        AssignService $assignService,
        UserService $userService,
        InquiryService $inquiryService,
        InquiryPhotosService $inquiryPhotosService,
        InquiryProductService $inquiryProductService,
        WhatsappService $whatsappService
    )
    {
        $this->imageService = $imageService;
        $this->cityService = $cityService;
        $this->businessService = $businessService;
        $this->productService = $productService;
        $this->statusService = $statusService;
        $this->assignService = $assignService;
        $this->userService = $userService;
        $this->inquiryService = $inquiryService;
        $this->inquiryPhotosService = $inquiryPhotosService;
        $this->inquiryProductService = $inquiryProductService;
        $this->whatsappService = $whatsappService;
    }

    public function index(Request $request)
    {
        $total_inquiry = Inquiry::count();
        $statuses = $this->statusService->getAllStatusCount();
        $total_users = User::count();
        return view('admin.index')->with('total_inquiry', $total_inquiry)->with('statuses', $statuses)->with('total_users', $total_users);
    }
    public function cities(Request $request)
    {
        $cities = $this->cityService->getAllCities();
        return view('admin.cities.index')->with('cities', $cities);
    }
    public function addCity(Request $request)
    {
        return view('admin.cities.add');
    }
    public function saveCity(Request $request)
    {
        $data = $request->all();
        $data['name'] = $request->city;
        $this->cityService->create($data);
        $request->session()->put('message', 'City has been added successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('admin.cities');
    }
    public function editCity(Request $request, $id)
    {
        try{
            $city = $this->cityService->getCityById($id);
            if(!$city){
                throw new BadRequestException('Invalid Request id');
            }
            return view('admin.cities.edit')->with('city', $city);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.cities');
        }
    }
    public function updateCity(Request $request)
    {
        try{
            $city = $this->cityService->getCityById($request->id);
            if(!$city){
                throw new BadRequestException('Invalid Request id');
            }
            $data['name'] = $request->city;
            $this->cityService->update($city, $data);
            $request->session()->put('message', 'City has been updated successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.cities');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.cities');
        }
    }
    public function deleteCity(Request $request, $id)
    {
        try{
            $city = $this->cityService->getCityById($id);
            if(!$city){
                throw new BadRequestException('Invalid Request id.');
            }
            $this->cityService->delete($city);
            $request->session()->put('message', 'City has been deleted successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.cities');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.cities');
        }
    }
    public function business(Request $request)
    {
        $businesses = $this->businessService->getAllBusiness();
        return view('admin.business.index')->with('businesses', $businesses);
    }
    public function addBusiness(Request $request)
    {
        return view('admin.business.add');
    }
    public function saveBusiness(Request $request)
    {
        $data = $request->all();
        $data['name'] = $request->name;
        $this->businessService->create($data);
        $request->session()->put('message', 'Business has been added successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('admin.business');
    }
    public function editBusiness(Request $request, $id)
    {
        try{
            $business = $this->businessService->getBusinessById($id);
            if(!$business){
                throw new BadRequestException('Invalid Request id');
            }
            return view('admin.business.edit')->with('business', $business);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.business');
        }
    }
    public function updateBusiness(Request $request)
    {
        try{
            $business = $this->businessService->getBusinessById($request->id);
            if(!$business){
                throw new BadRequestException('Invalid Request id');
            }
            $data['name'] = $request->name;
            $this->businessService->update($business, $data);
            $request->session()->put('message', 'Business has been updated successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.business');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.business');
        }
    }
    public function deleteBusiness(Request $request, $id)
    {
        try{
            $business = $this->businessService->getBusinessById($id);
            if(!$business){
                throw new BadRequestException('Invalid Request id.');
            }
            $this->businessService->delete($business);
            $request->session()->put('message', 'Business has been deleted successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.business');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.business');
        }
    }
    public function products(Request $request)
    {
        $products = $this->productService->getAllProducts();
        return view('admin.products.index')->with('products', $products);
    }
    public function addProduct(Request $request)
    {
        return view('admin.products.add');
    }
    public function saveProduct(Request $request)
    {
        $data = $request->all();
        $data['name'] = $request->name;
        $this->productService->create($data);
        $request->session()->put('message', 'Product has been added successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('admin.products');
    }
    public function editProduct(Request $request, $id)
    {
        try{
            $product = $this->productService->getProductById($id);
            if(!$product){
                throw new BadRequestException('Invalid Request id');
            }
            return view('admin.products.edit')->with('product', $product);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.products');
        }
    }
    public function updateProduct(Request $request)
    {
        try{
            $product = $this->productService->getProductById($request->id);
            if(!$product){
                throw new BadRequestException('Invalid Request id');
            }
            $data['name'] = $request->name;
            $this->productService->update($product, $data);
            $request->session()->put('message', 'Product has been updated successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.products');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.products');
        }
    }
    public function deleteProduct(Request $request, $id)
    {
        try{
            $product = $this->productService->getProductById($id);
            if(!$product){
                throw new BadRequestException('Invalid Request id.');
            }
            $this->productService->delete($product);
            $request->session()->put('message', 'Product has been deleted successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.products');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.products');
        }
    }
    public function status(Request $request)
    {
        $statuses = $this->statusService->getAllStatus();
        return view('admin.status.index')->with('statuses', $statuses);
    }
    public function addStatus(Request $request)
    {
        return view('admin.status.add');
    }
    public function saveStatus(Request $request)
    {
        $data = $request->all();
        $data['name'] = $request->name;
        $this->statusService->create($data);
        $request->session()->put('message', 'Status has been added successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('admin.status');
    }
    public function editStatus(Request $request, $id)
    {
        try{
            $status = $this->statusService->getStatusById($id);
            if(!$status){
                throw new BadRequestException('Invalid Request id');
            }
            return view('admin.status.edit')->with('status', $status);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.status');
        }
    }
    public function updateStatus(Request $request)
    {
        try{
            $status = $this->statusService->getStatusById($request->id);
            if(!$status){
                throw new BadRequestException('Invalid Request id');
            }
            $data['name'] = $request->name;
            $this->statusService->update($status, $data);
            $request->session()->put('message', 'Status has been updated successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.status');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.status');
        }
    }
    public function deleteStatus(Request $request, $id)
    {
        try{
            $status = $this->statusService->getStatusById($id);
            if(!$status){
                throw new BadRequestException('Invalid Request id.');
            }
            $this->statusService->delete($status);
            $request->session()->put('message', 'Status has been deleted successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.status');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.status');
        }
    }
    public function assign(Request $request)
    {
        $assigns = $this->assignService->getAllAssign();
        return view('admin.assign.index')->with('assigns', $assigns);
    }
    public function addAssign(Request $request)
    {
        return view('admin.assign.add');
    }
    public function saveAssign(Request $request)
    {
        $data = $request->all();
        $data['name'] = $request->name;
        $this->assignService->create($data);
        $request->session()->put('message', 'Assign name has been added successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('admin.assign');
    }
    public function editAssign(Request $request, $id)
    {
        try{
            $assign = $this->assignService->getAssignById($id);
            if(!$assign){
                throw new BadRequestException('Invalid Request id');
            }
            return view('admin.assign.edit')->with('assign', $assign);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.assign');
        }
    }
    public function updateAssign(Request $request)
    {
        try{
            $assign = $this->assignService->getAssignById($request->id);
            if(!$assign){
                throw new BadRequestException('Invalid Request id');
            }
            $data['name'] = $request->name;
            $this->assignService->update($assign, $data);
            $request->session()->put('message', 'Assign name has been updated successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.assign');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.assign');
        }
    }
    public function deleteAssign(Request $request, $id)
    {
        try{
            $assign = $this->assignService->getAssignById($id);
            if(!$assign){
                throw new BadRequestException('Invalid Request id.');
            }
            $this->assignService->delete($assign);
            $request->session()->put('message', 'Assign name has been deleted successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.assign');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.assign');
        }
    }
    public function getUsers()
    {
        $users = $this->userService->getAllUsers();
        return view('admin.users.index')->with('users', $users);
    }
    public function fetchUsers(Request $request)
    {
        $users = $this->userService->getUsersByFilter($request);
        return view('admin.users.result')->with('users', $users)->render();
    }
    public function addUser()
    {
        $users = $this->userService->getAllUsers();
        return view('admin.users.add')->with('users', $users);
    }
    public function saveUser(UserRequest $request)
    {
        $user = $this->userService->create($request);
        $request->session()->put('message', 'User has been added successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('admin.users');
    }
    public function editUser(Request $request, $id)
    {
        try{
            $user = $this->userService->getUserById($id);
            if(!$user){
                throw new BadRequestException('Invalid Request id');
            }
            $users = $this->userService->getAllUsers();
            return view('admin.users.edit')->with('user', $user)->with('users', $users);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.users');
        }
    }
    public function updateUser(Request $request)
    {
        try{
            $user = $this->userService->getUserById($request->id);
            if(!$user){
                throw new BadRequestException('Invalid Request id');
            }
            $data['name'] = $request->name;
            $data['contact_person'] = $request->contact_person;
            $data['city'] = $request->city;
            $data['state'] = $request->state;
            $data['country'] = $request->country;
            $data['email'] = $request->email;
            $data['work_with'] = $request->work_with;
            $data['current_work'] = $request->current_work;
            $data['up_line'] = $request->up_line;
            $data['pan_card_number'] = $request->pan_card_number;
            $data['remarks'] = $request->remarks;
            if($request->has('pan_card_attachment')){
                $filepath = public_path('assets/' . $user->pan_card_attachment);
                $this->imageService->deleteFile($filepath);
                $filename = $this->imageService->uploadFile($request->pan_card_attachment, "assets/document");
                $data['pan_card_attachment'] = '/document/'.$filename;
            }
            if($user->isUser()) {
                $data['status'] = $request->active;
            }
            $this->userService->update($user, $data);
            $request->session()->put('message', 'User has been updated successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.users');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.users');
        }
    }
    public function deleteUser(Request $request, $id)
    {
        try{
            $user = $this->userService->getUserById($id);
            if(!$user){
                throw new BadRequestException('Invalid Request id.');
            }
            $this->userService->delete($user);
            $request->session()->put('message', 'User has been deleted successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.users');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.users');
        }
    }
    public function getInquiries(Request $request)
    {
        $statuses = $this->statusService->getAllStatus();
        $status_id = "";
        if( $request->has('status') ) {
            $status_id = $request->input('status');
            $inquiries = $this->inquiryService->getInquiriesByStatus($status_id);
        } else {
            $inquiries = $this->inquiryService->getAllInquiries();
        }
        return view('admin.inquiries.index')->with('statuses', $statuses)->with('status_id', $status_id)->with('inquiries', $inquiries);
    }
    public function fetchInquiriesByStatus(Request $request)
    {
        $status_id = $request->status_id;
        $inquiries = $this->inquiryService->getAllInquiries();
        if($status_id) {
            $inquiries = $this->inquiryService->getInquiriesByStatus($status_id);
        }
        return view('admin.inquiries.list')->with('inquiries', $inquiries)->render();
    }
    public function addInquiry(Request $request)
    {
        $businesses = $this->businessService->getAllBusiness();
        $products = $this->productService->getAllProducts();
        $statuses = $this->statusService->getAllStatus();
        $users = $this->userService->getAllUsers();
        return view('admin.inquiries.add')->with('businesses', $businesses)->with('products', $products)->with('statuses', $statuses)->with('users', $users);
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
		if($request->has('requirements')){
			$filename_req = $this->imageService->uploadFile($request->requirements, "assets/inquiry/requirements");
            $data['requirements'] = '/inquiry/requirements/'.$filename_req;
        }
		if($request->has('quotation')){
			$filename_quo = $this->imageService->uploadFile($request->quotation, "assets/inquiry/quotation");
            $data['quotation'] = '/inquiry/quotation/'.$filename_quo;
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
        $request->session()->put('message', 'inquiry has been generated successfully.');
        $request->session()->put('alert-type', 'alert-success');
        return redirect()->route('admin.inquiries');
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
            return view('admin.inquiries.edit')->with('inquiry', $inquiry)->with('businesses', $businesses)->with('products', $products)->with('statuses', $statuses)->with('users', $users);
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.inquiries');
        }
    }
    public function updateInquiry(Request $request)
    {
        try{
            $inquiry = $this->inquiryService->getInquiryById($request->id);
            if(!$inquiry){
                throw new BadRequestException('Invalid Request id');
            }
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
			if($request->has('requirements')){
                $filepath = public_path('assets/' . $inquiry->requirements);
                $this->imageService->deleteFile($filepath);
			    $filename_req = $this->imageService->uploadFile($request->requirements, "assets/inquiry/requirements");
                $data['requirements'] = '/inquiry/requirements/'.$filename_req;
			}
			if($request->has('quotation')){
                $filepath2 = public_path('assets/' . $inquiry->quotation);
                $this->imageService->deleteFile($filepath2);
				$filename_quo = $this->imageService->uploadFile($request->quotation, "assets/inquiry/quotation");
                $data['quotation'] = '/inquiry/quotation/'.$filename_quo;
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
            $request->session()->put('message', 'inquiry has been updated successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.inquiries');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.inquiries');
        }
    }
    public function deleteInquiry(Request $request, $id)
    {
        try{
            $inquiry = $this->inquiryService->getInquiryById($id);
            if(!$inquiry){
                throw new BadRequestException('Invalid Request id.');
            }
            $this->inquiryService->delete($inquiry);
            $request->session()->put('message', 'Inquiry has been deleted successfully.');
            $request->session()->put('alert-type', 'alert-success');
            return redirect()->route('admin.inquiries');
        }catch(\Exception $e){
            $request->session()->put('message', $e->getMessage());
            $request->session()->put('alert-type', 'alert-warning');
            return redirect()->route('admin.inquiries');
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
        $path = public_path('assets/' . $inquiry->requirements);
        if (file_exists($path)) {
            $this->imageService->deleteFile($path);
        }
        $data['requirements'] = NULL;
        $this->inquiryService->update($inquiry, $data);
        return response()->json(['success' => true]);
    }
    public function deleteQuoPDF(Request $request)
    {
        $inquiry = $this->inquiryService->getInquiryById($request->id);
        $path = public_path('assets/' . $inquiry->quotation);
        if (file_exists($path)) {
            $this->imageService->deleteFile($path);
        }
        $data['quotation'] = NULL;
        $this->inquiryService->update($inquiry, $data);
        return response()->json(['success' => true]);
    }
    public function deletedInquiries()
    {
        $inquiries = Inquiry::onlyTrashed()->get();
        return view('admin.inquiries.deleted')->with('inquiries', $inquiries);
    }
}
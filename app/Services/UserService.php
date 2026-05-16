<?php

namespace App\Services;

use App\Models\Role;
use App\Models\User;
use App\Services\UploadImageService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    private $role_id = Role::USER_ROLE_ID;
    private $imageService;

    public function __construct(
        UploadImageService $imageService
    )
    {
        $this->imageService = $imageService;
    }

    public function create($request)
    {
        return DB::transaction(function () use ($request) {
            $user = new User();
            $user->role_id = $this->role_id;
            $user->up_line = $request->up_line;
            $user->name = $request->name;
            $user->contact_person = $request->contact_person;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->country = $request->country;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->phone = $request->phone;
            $user->work_with = $request->work_with;
            $user->current_work = $request->current_work;
            $user->pan_card_number = $request->pan_card_number;
            $user->remarks = $request->remarks;
            $user->status = $request->active;
            if($request->has('pan_card_attachment')){
                $filename = $this->imageService->uploadFile($request->pan_card_attachment, "assets/document");
                $user->pan_card_attachment = '/document/'.$filename;
            }
            $user->save();
            return $user;
        });
    }
    public function getUserById($id)
    {
        return User::find($id);
    }
    public function update($user, $data)
    {
        return $user->update($data);
    }
    public function delete($user)
    {
        return $user->delete($user);
    }
    public function getAllUsers($per_page = -1)
    {
        if($per_page == -1){
            return User::orderBy('created_at', 'desc')->get();    
        }
        return User::orderBy('created_at', 'desc')->paginate($per_page);
    }
    public function getUsersByFilter($request)
    {
        $filter_query = User::orderBy('created_at','desc');
        if($request->has('status') && $request->status != ''){
            $filter_query = $filter_query->where('status', $request->status);
        }
        if($request->has('up_line') && $request->up_line != ''){
            $filter_query = $filter_query->where('up_line', $request->up_line);
        }
        return $filter_query->select('*')->get();
    }
    public function getTeamByUser($user_id)
    {
        return User::where('up_line', $user_id)->orderBy('created_at', 'desc')->get();
    }
}

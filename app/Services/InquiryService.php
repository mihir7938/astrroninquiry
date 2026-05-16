<?php

namespace App\Services;

use App\Models\Inquiry;

class InquiryService
{

    public function getAllInquiries($per_page = -1)
    {
        if($per_page == -1){
            return Inquiry::orderBy('created_at','desc')->get();    
        }
        return Inquiry::orderBy('created_at','desc')->paginate($per_page);
    }

    public function getInquiryById($id)
    {
        return Inquiry::find($id);
    }

    public function create($data)
    {
        return Inquiry::create($data);
    }

    public function update($inquiry, $data)
    {
        return $inquiry->update($data);
    }

    public function delete($inquiry)
    {
        return $inquiry->delete($inquiry);
    }

    public function getInquiriesByStatus($status_id)
    {
        return Inquiry::where('status_id', $status_id)->orderBy('created_at','desc')->get();
    }

    public function getInquiriesByUser($user_id)
    {
        return Inquiry::where('user_id', $user_id)->orderBy('created_at','desc')->get();
    }

    public function getInquiriesByUserByStatus($user_id, $status_id)
    {
        return Inquiry::where('user_id', $user_id)->where('status_id', $status_id)->orderBy('created_at','desc')->get();
    }

    public function getInquiriesByUserByFilter($request, $user_id)
    {
        $filter_query = Inquiry::where('user_id', '=', $user_id)->orderBy('created_at','desc');
        if($request->has('status_id') && $request->status_id != ''){
            $filter_query = $filter_query->where('status_id', $request->status_id);
        }
        if($request->followup_start_date && $request->followup_end_date){
            $startDate = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_start_date)));
            $endDate = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_end_date)));
            $filter_query = $filter_query->where(function ($query) use ($startDate, $endDate) { 
                $query->whereBetween('followup_date_1', [$startDate, $endDate])
                    ->orWhereBetween('followup_date_2', [$startDate, $endDate])
                    ->orWhereBetween('followup_date_3', [$startDate, $endDate])
                    ->orWhereBetween('followup_date_4', [$startDate, $endDate])
                    ->orWhereBetween('followup_date_5', [$startDate, $endDate]);
            });
        }
        return $filter_query->select('*')->get();
    }

    public function getInquiriesByAssign($assign_id, $user_id)
    {
        return Inquiry::where(function ($query) use ($assign_id, $user_id) { 
            $query->where('assign_id', '=', $assign_id) 
                ->orWhere('user_id', '=', $user_id);
            })->where(function ($query) { 
                $query->whereColumn('assign_id','!=','user_id'); 
            })->orderBy('created_at','desc')->get();
    }

    public function getInquiriesByAssignByStatus($request, $assign_id)
    {
        $filter_query = Inquiry::where(function ($query) use ($request, $assign_id) { 
            $query->where('assign_id', '=', $assign_id) 
                ->orWhere('user_id', '=', $assign_id);
            })->where(function ($query) { 
                $query->whereColumn('assign_id','!=','user_id'); 
            })->orderBy('created_at','desc');

        if($request->has('status_id') && $request->status_id != ''){
            $filter_query = $filter_query->where('status_id', $request->status_id);
        }
        if($request->has('assign_type') && $request->assign_type != ''){
            if($request->assign_type == 'In') {
                $filter_query = $filter_query->where('assign_id', $assign_id);
            } else if($request->assign_type == 'Out') {
                $filter_query = $filter_query->where('user_id', $assign_id);
            }
        }
        if($request->followup_start_date && $request->followup_end_date){
            $startDate = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_start_date)));
            $endDate = date("Y-m-d", strtotime(str_replace('/', '-', $request->followup_end_date)));
            $filter_query = $filter_query->where(function ($query) use ($startDate, $endDate) { 
                $query->whereBetween('followup_date_1', [$startDate, $endDate])
                    ->orWhereBetween('followup_date_2', [$startDate, $endDate])
                    ->orWhereBetween('followup_date_3', [$startDate, $endDate])
                    ->orWhereBetween('followup_date_4', [$startDate, $endDate])
                    ->orWhereBetween('followup_date_5', [$startDate, $endDate]);
            });
        }
        return $filter_query->select('*')->get();
    }

    public function getTotalInquiriesByStatus($status_id)
    {
        return Inquiry::where('status_id', $status_id)->count();
    }

    public function getTotalInquiriesByUser($user_id)
    {
        return Inquiry::where('user_id', $user_id)->count();
    }

    public function getTotalInquiriesByAssign($assign_id, $user_id)
    {
        return Inquiry::where(function ($query) use ($assign_id, $user_id) { 
            $query->where('assign_id', '=', $assign_id) 
                ->orWhere('user_id', '=', $user_id);
            })->where(function ($query) { 
                $query->whereColumn('assign_id','!=','user_id'); 
            })->count();
    }

    public function getTotalInquiriesByUserByStatus($user_id, $status_id)
    {
        return Inquiry::where('user_id', $user_id)->where('status_id', $status_id)->count();
    }
    
}

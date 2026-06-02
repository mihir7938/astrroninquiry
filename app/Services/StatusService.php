<?php

namespace App\Services;

use App\Models\Status;

class StatusService
{

    public function getAllStatus($per_page = -1)
    {
        if($per_page == -1){
            return Status::orderBy('created_at', 'asc')->get();    
        }
        return Status::orderBy('created_at', 'asc')->paginate($per_page);
    }

    public function getStatusById($id)
    {
        return Status::find($id);
    }

    public function create($data)
    {
        return Status::create($data);
    }

    public function update($status, $data)
    {
        return $status->update($data);
    }

    public function delete($status)
    {
        return $status->delete($status);
    }

    public function getAllStatusCount($per_page = -1)
    {
        return Status::withCount('inquiries')->orderBy('created_at', 'asc')->get();
    }

    public function getAllStatusByUserAssign($user_id)
    {
        $query = Status::withCount([
        'inquiries' => function ($q) use ($user_id) {
            $q->where(function ($query) use ($user_id) {
                $query->where('user_id', $user_id)
                      ->orWhere('assign_id', $user_id);
            });
        }
        ])->orderBy('created_at', 'asc');
        return $query->get();
    }
}

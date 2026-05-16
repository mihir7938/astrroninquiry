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
}

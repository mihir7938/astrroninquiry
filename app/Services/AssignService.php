<?php

namespace App\Services;

use App\Models\Assign;

class AssignService
{

    public function getAllAssign($per_page = -1)
    {
        if($per_page == -1){
            return Assign::orderBy('created_at', 'desc')->get();    
        }
        return Assign::orderBy('created_at', 'desc')->paginate($per_page);
    }

    public function getAssignById($id)
    {
        return Assign::find($id);
    }

    public function create($data)
    {
        return Assign::create($data);
    }

    public function update($assign, $data)
    {
        return $assign->update($data);
    }

    public function delete($assign)
    {
        return $assign->delete($assign);
    }
}

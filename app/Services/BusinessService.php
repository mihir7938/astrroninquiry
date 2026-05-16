<?php

namespace App\Services;

use App\Models\Business;

class BusinessService
{

    public function getAllBusiness($per_page = -1)
    {
        if($per_page == -1){
            return Business::orderByRaw('field(name, "Other")')->orderBy('name')->get();    
        }
        return Business::orderByRaw('field(name, "Other")')->orderBy('name')->paginate($per_page);
    }

    public function getBusinessById($id)
    {
        return Business::find($id);
    }

    public function create($data)
    {
        return Business::create($data);
    }

    public function update($business, $data)
    {
        return $business->update($data);
    }

    public function delete($business)
    {
        return $business->delete($business);
    }
}

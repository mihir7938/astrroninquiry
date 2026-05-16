<?php

namespace App\Services;

use App\Models\City;

class CityService
{

    public function getAllCities($per_page = -1)
    {
        if($per_page == -1){
            return City::orderBy('created_at', 'desc')->get();    
        }
        return City::orderBy('created_at', 'desc')->paginate($per_page);
    }

    public function getCityById($id)
    {
        return City::find($id);
    }

    public function create($data)
    {
        return City::create($data);
    }

    public function update($cities, $data)
    {
        return $cities->update($data);
    }

    public function delete($cities)
    {
        return $cities->delete($cities);
    }
}

<?php

namespace App\Services;

use App\Models\InquiryProduct;

class InquiryProductService
{

    public function getAllInquiryProducts($per_page = -1)
    {
        if($per_page == -1){
            return InquiryProduct::orderBy('created_at', 'desc')->get();    
        }
        return InquiryProduct::orderBy('created_at', 'desc')->paginate($per_page);
    }

    public function getInquiryProductById($id)
    {
        return InquiryProduct::find($id);
    }

    public function create($data)
    {
        return InquiryProduct::create($data);
    }

    public function update($inquiry_product, $data)
    {
        return $inquiry_product->update($data);
    }

    public function delete($inquiry_product)
    {
        return $inquiry_product->delete($inquiry_product);
    }
    
}

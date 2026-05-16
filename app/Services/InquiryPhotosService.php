<?php

namespace App\Services;

use App\Models\InquiryPhoto;

class InquiryPhotosService
{

    public function getAllInquiryPhotos($per_page = -1)
    {
        if($per_page == -1){
            return InquiryPhoto::orderBy('created_at', 'desc')->get();    
        }
        return InquiryPhoto::orderBy('created_at', 'desc')->paginate($per_page);
    }

    public function getInquiryPhotoById($id)
    {
        return InquiryPhoto::find($id);
    }

    public function create($data)
    {
        return InquiryPhoto::create($data);
    }

    public function update($inquiry_photo, $data)
    {
        return $inquiry_photo->update($data);
    }

    public function delete($inquiry_photo)
    {
        return $inquiry_photo->delete($inquiry_photo);
    }
    
}

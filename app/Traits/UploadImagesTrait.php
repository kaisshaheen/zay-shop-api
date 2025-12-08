<?php
namespace App\Traits;

use Illuminate\Http\Request;


trait UploadImagesTrait{
    public function uploadImage(Request $request , $folderName){
        if($request->hasFile("image_path")){
            $photo_name = $request->file('image_path')->getClientOriginalName();
            $photo_upload = $request->file('image_path')->storeAs($folderName, $photo_name , "public");
        }
        return $photo_upload;
    }
}
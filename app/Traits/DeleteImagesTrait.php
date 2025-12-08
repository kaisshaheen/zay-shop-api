<?php
namespace App\Traits;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

trait DeleteImagesTrait{

    public function DeleteImage(Request $request , Product $product){
        if ($request->hasFile("image_path")) {
        
            if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
                Storage::disk('public')->delete($product->image_path);
             }
            $image_path = $this->uploadImage($request, "images");         
            $data["image_path"] = $image_path; 
            return $data;
        }
    }

}
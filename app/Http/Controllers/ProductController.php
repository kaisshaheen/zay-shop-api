<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use App\Traits\DeleteImagesTrait;
use App\Traits\UploadImagesTrait;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller implements HasMiddleware
{

    use UploadImagesTrait , DeleteImagesTrait;
    
    
    public static function middleware(): array
    {
        return [
            new Middleware('auth:sanctum',  except: ['index' , 'show'])
        ];
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::with("category")->latest()->get();
        return response()->json(['products' => $products]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        $data = $request -> validated();

        $image_path = $this->uploadImage($request , "products"); 
                
        $data["image_path"] = $image_path;

        $product =$request->admin()->product()->create($data);

         return response()->json(['product' => $product, 'category' => $product->category], 201); 
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
       $product->load('category');
       return response()->json(['products' => $product]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        Gate::authorize("modify" , $product);
        
        $data = $request->validated(); 

        if($request->hasFile("image_path")){
            $this->DeleteImage($request , $product);

            $image_path = $this->uploadImage($request , "products"); 
                
            $data["image_path"] = $image_path;
        }

        $product->update($data);
        return response()->json(['product' => $product->load('category')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        Gate::authorize("modify" , $product);
        if ($product->image_path && Storage::disk('public')->exists($product->image_path)) {
            Storage::disk('public')->delete($product->image_path);
        }
        $product -> delete();
        return response()->json([
            "message" => "The Product Was Deleted"
        ]);
    }


    public function deleteAllProduct(){
        $allProducts = Product::all();
        $paths = $allProducts->pluck('image_path')->filter()->toArray();

        if (!empty($paths)) {
            Storage::disk('public')->delete($paths);
        }

        Product::truncate();
        return response()->json([
            "message" => "All products have been been deleted"
        ]);
    }
}

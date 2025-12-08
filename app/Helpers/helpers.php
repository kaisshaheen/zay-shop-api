<?php

namespace App\Helpers;

use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

function getUserCart(){
    $user = Auth::guard("sanctum")->user();
    if(!$user){
        return null;
    }

    return $user->cart()->firstOrCreate();
}


function findCartItem($cart, Product $product, array $data){
    return $cart->items()
        ->where('product_id', $product->id)
        ->where('size', $data['size'])
        ->where('color', $data['color'])
        ->first();
}

function exceedsStock(Product $product, int $quantity): bool{
    return $quantity > $product->stock;
}

function stockExceeded(Product $product): JsonResponse{
    return response()->json([
        'error' => "Requested quantity exceeds available stock ({$product->stock})."
    ], 400);
}

function unauthorized(): JsonResponse{
    return response()->json([
        'error' => 'User not authenticated or cart not found.'
    ], 401);
}
function ensureStock(Product $product, int $quantity): void{
    if (exceedsStock($product , $quantity)) {
        stockExceeded($product);
    }
}
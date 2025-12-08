<?php

namespace App\Http\Controllers;

use App\Http\Requests\CartRequest;
use App\Models\CartItem;
use App\Models\Product;
use App\Services\Cart\CartService;



class CartController extends Controller
{

    private $cartService;
    public function __construct(CartService $cartService) {
        $this->cartService = $cartService;
    }
    public function index(){
        
    
        return response()->json([
            'message' => 'Cart retrieved successfully',
            'cart_id' => $this->cartService->getCart()->id,
            'cart_items' => $this->cartService->getCartItems(),
            'total' => $this->cartService->getCartTotal(),
        ], 200);
    }

    public function add(CartRequest $request , $id){

        $product = Product::findOrFail($id);

        $cartItem = $this->cartService->addItem($product , $request->validated());


        return response()->json(['message' => 'A new item has been added to cart' ,'cart_item' => $cartItem->load('product')], 201);
    }


    public function show(CartItem $cartItem){
        $cartItem->load('product');
        return response()->json(["cart_item" => $cartItem]);
    }


    public function update(CartRequest $request , Product $product){
        $result = $this->cartService->updateItem($product, $request->validated());

       if (is_array($result) && $result['deleted'] ?? false) {
            return response()->json(['message' => "{$product->name} removed from cart"]);
        }

        return response()->json(['cart_item' => $result]);
    }


    public function remove(CartItem $item)
    {
        $this->cartService->removeItem($item);
        return response()->json(['message' => 'Item removed']);
    }
      
    
    public function clear(){
        $this->cartService->clearCart();

        return response()->json(['message' => 'Cart cleared']);
    }

    // public function remove(Product $product)
    // {
    //     $cart = getUserCart();

    //     $deleted = $cart->items()->where('product_id', $product->id)->delete();

    //     if ($deleted) {
    //         return redirect()->back()->with('success', $product->name . ' removed from cart.');
    //     }

    //     return redirect()->back()->with('error', 'Item not found in your cart.');
    // }
}

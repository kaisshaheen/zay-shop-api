<?php

namespace App\Services\Order;

use App\Models\Order;
use App\Services\Cart\CartService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrderService{

    private $cartService;
    public function __construct(CartService $cartService){
        $this->cartService = $cartService;
    } 


    public function checkout(array $addressDetails){
        
        $items = $this->cartService->getCartItems();
        $total = $this->cartService->getCartTotal();
        $user =  Auth::guard("sanctum")->user(); 

        if ($items->isEmpty()) {
            throw new \Exception('Cannot checkout with an empty cart.');
        }

        
        DB::beginTransaction();

        try {
            $order = Order::create([
                'user_id' => $user->id,
                'total_price' => $total,
                'shipping_address' => $addressDetails['shipping_address'] ?? null,
                'billing_address' => $addressDetails['billing_address'] ?? null,
                'status' => 'pending', // Status before payment
            ]);

           
            foreach ($items as $cartItem) {
                $order->orderItems()->create([
                    'product_id' => $cartItem->product_id,
                    'quantity' => $cartItem->quantity,
                    'size' => $cartItem->size,
                    'color' => $cartItem->color,
                    'price' => $cartItem->product->price,
                ]);
                $cartItem->product->decrement('stock', $cartItem->quantity);
            }

            $this->cartService->clearCart();

            DB::commit();

            return $order;

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
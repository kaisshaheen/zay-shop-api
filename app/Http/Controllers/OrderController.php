<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Order\OrderService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class OrderController extends Controller
{
    public function __construct(private OrderService $orderService){}
    

    public function processCheckout(Request $request){
        $field = $request->validate([
            'shipping_address' => 'required|string|max:255',
            'billing_address' => 'sometimes|string|max:255 | nullable',
        ]);

        try{
            $order = $this->orderService->checkout($field);

            return response()->json([
                'message' => 'Order placed successfully',
                'order_id' => $order->id,
                'total_price' => $order->total_price,
            ] , 201);
        }catch(\Exception $e){
            return response()->json([
                'message' => 'Checkout failed',
                'error' => $e->getMessage()
            ], 400);
        }
    }

    public function index(){
        $orders = Auth::guard("sanctum")->user()->orders()->with('orderItems.product')->get();
        return response()->json(["orders" => $orders]);
    }

    public function show(Order $order){

        Gate::authorize("orderPolicy" , $order);

        $order->load('orderItems.product');
        return response()->json(['order' => $order]);

    }
}

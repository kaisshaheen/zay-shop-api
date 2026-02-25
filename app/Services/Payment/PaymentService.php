<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Stripe;

class PaymentService
{
    public function createCheckoutSession(Order $order)
    {
         // Implement checkout logic here
        Stripe::setApiKey(config('services.stripe.secret'));

        if ($order->session_id) {
            return response()->json([
                'message' => 'Checkout session already exists'
            ], 400);
        }
        
        $line_items = $order->orderItems()->with('product')->get()->map(function($item) {
             return [
                'price_data' => [
                    'currency' => 'usd',
                    'product_data' => [
                        'name' => $item->product->name,
                    ],
                    'unit_amount' => (int) ($item->product->price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        })->toArray();


        if (!$order) {
            return response()->json(['message' => 'Invalid order'], 404);
        }


        $checkout_session = Session::create([
            'line_items' =>$line_items,
            'mode' => 'payment',
            'customer_creation' => 'always',
            'success_url' => config('app.frontend_url').'/payment-success?order_id='.$order->id,
            'cancel_url' => config('app.frontend_url'). '/payment-cancel',
            'metadata' => [
                'order_id' => $order->id,
            ],
            'payment_intent_data' => [
                'metadata' => [
                    'order_id' => $order->id,
                    'user_id' => Auth::id(),
                ],
            ],
        ]);

        $order->update([
            'session_id' => $checkout_session->id,
        ]);
        
            return $checkout_session->url;
    }

    public function handleSuccessfulPayment($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;
        

        if (!$orderId) {
            return ;
        }
        
        $order = Order::find($orderId);

        if (!$order || $order->status === 'paid') {
            return;
        }
        
        $order->update([
            'status' => 'paid',
            'paid_at' => now(),
            'stripe_payment_intent_id' => $paymentIntent->id,
            'amount_paid' => $paymentIntent->amount_received / 100,
            'currency' => $paymentIntent->currency,
        ]);
    }

    public function handleFailedPayment($paymentIntent)
    {
        $orderId = $paymentIntent->metadata->order_id ?? null;

        if (!$orderId) {
            return;
        }

        $order = Order::find($orderId);

        if ($order && $order->status === 'pending') {
            $order->update([
                'status' => 'failed',
                'stripe_payment_intent_id' => $paymentIntent->id,
            ]);
        }
    }
}
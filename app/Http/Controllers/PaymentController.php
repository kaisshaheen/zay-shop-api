<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\Payment\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Checkout\Session;
use Stripe\Customer;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PaymentController extends Controller
{


    public function __construct(private PaymentService $paymentService)
    {
        //
    }
    
    public function checkout(int $id)
    {
        $order = Auth::user()->orders()->where('id', $id)->where('status' , 'pending')->first();
        

        if (!$order) {
            return response()->json(['message' => 'Invalid order'], 404);
        }

        try {
            $checkoutUrl = $this->paymentService->createCheckoutSession($order);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }

        // Redirect to the appropriate page after checkout
        return response()->json([
            'checkout_url' => $checkoutUrl
        ]);

    }

    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $endpoint_secret = config('services.stripe.webhook_secret');
        $event = null;

        try{
            $event = Webhook::constructEvent(
                $payload, $signature, $endpoint_secret
            );
        }catch(\UnexpectedValueException $e){
            // Invalid payload
            return response()->json(['message' => 'Invalid payload'], 400);
        }catch(SignatureVerificationException $e){
            //invalid signature
            return response()->json(['message' => 'Invalid signature'], 400);
        }

        switch($event->type){
            case 'payment_intent.succeeded':
                $this->paymentService->handleSuccessfulPayment($event->data->object);
                break;
            case 'payment_intent.payment_failed':
                $this->paymentService->handleFailedPayment($event->data->object);
                break;
        }

        return response()->json(['received' => true]);
    }
}

<?php

namespace App\Http\Controllers\api\v1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\Checkout\Session;

class StripePaymentController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        Stripe::setApiKey(config('services.stripe.secret'));

        $checkout_session = Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => 'usd',
                    'unit_amount' => $request->input('order_amount') * 100,
                    'product_data' => [
                        'name' => 'Product Name', // Replace with your product name
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => $request->input('success_url', '/success'), // Replace with your success URL
            'cancel_url' => $request->input('cancel_url', '/cancel'), // Replace with your cancel URL
        ]);

        return response()->json(['id' => $checkout_session->id]);
    }
}

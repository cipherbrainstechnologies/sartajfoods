<?php

namespace App\Http\Controllers\api;

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
                    'currency' => 'jpy',
                    'unit_amount' => 1000,
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

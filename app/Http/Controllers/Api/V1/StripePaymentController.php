<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\CentralLogics\Helpers;
use Illuminate\Http\Request;
use App\Model\BusinessSetting;
use App\Model\Order;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Stripe\PaymentIntent;
use Stripe\PaymentLink;
use Stripe\Charge;
use Stripe\Stripe;

class StripePaymentController extends Controller
{
    // public function createPaymentLink(Request $request)
    // {
    //     try {
    //         $config = Helpers::get_business_settings('stripe');
        
    //         Stripe::setApiKey($config['api_key']);

    //         $session = Http::post('https://api.stripe.com/v1/checkout/sessions', [
    //             'payment_method_types' => ['card'],
    //             'line_items' => [[
    //                 'price_data' => [
    //                     'currency' => 'usd',
    //                     'product_data' => [
    //                         'name' => 'Your Product Name',
    //                     ],
    //                     'unit_amount' => 1000, // amount in cents
    //                 ],
    //                 'quantity' => 1,
    //             ]],
    //             'mode' => 'payment',
    //             'success_url' => 'https://example.com/success', // Set your success URL
    //             'cancel_url' => 'https://example.com/cancel', // Set your cancel URL
    //         ])->json();
    
    //         // Return the payment link URL
    //         return response()->json(['payment_link' => $session['url'] ?? $session['checkout_url'] ?? null]);
    //     } catch (ApiErrorException $e) {
    //         // Handle error
    //         return response()->json(['error' => $e->getMessage()], 500);
    //     }
    // }

    public function createPaymentLink(Request $request,$order_id)
    {
        try {
            $tran = Str::random(6) . '-' . rand(1, 1000);
            $order_amount = $request['order_amount'];
            $callback = $request['callback'];
            $config = Helpers::get_business_settings('stripe');
            
            Stripe::setApiKey($config['api_key']);
            header('Content-Type: application/json');
            $currency_code = Helpers::get_business_settings('currency');
            $products = [];
            $orders = Order::with('details')->where('id', $order_id)->first();
            if (!empty($orders)) {
                $line_items = [];

                foreach ($orders['details'] as $key => $detail) {
                    $product = json_decode($detail['product_details'], true);

                    $line_items[] = [
                        'price_data' => [
                            'currency' => 'usd', // Adjust currency as needed
                            'unit_amount' => $product['actual_price'], // Convert price to cents
                            'product_data' => [
                                'name' => $product['translations'][0]['value'], // Use the product name
                                'images' => [$product['image'][0]], // Use the product image
                            ],
                        ],
                        'quantity' => $detail['quantity'], // Use the quantity from order details
                    ];
                }
                echo "<pre>";print_r($line_items);die;

                // Create a Checkout Session
                $checkout_session = \Stripe\Checkout\Session::create([
                    'payment_method_types' => ['card'],
                    'line_items' => $line_items,
                    'mode' => 'payment',
                    'success_url' => route('pay-stripe.success', ['callback' => $callback, 'transaction_reference' => $tran]),
                    'cancel_url' => url()->previous(),
                ]);

                // Return the payment link
                return response()->json(['payment_link' => $checkout_session->url]);
            }else{
                return response()->json(['error' => 'Order details not found'], 404);
            }
            // $checkout_session = \Stripe\Checkout\Session::create([
            //     'payment_method_types' => ['card'],
            //     'line_items' => [[
            //         'price_data' => [
            //             'currency' => $currency_code ?? 'usd',
            //             'unit_amount' => $order_amount,
            //             'product_data' => [
            //                 'name' => BusinessSetting::where(['key' => 'restaurant_name'])->first()->value,
            //                 'images' => [asset('storage/app/public/restaurant') . '/' . BusinessSetting::where(['key' => 'logo'])->first()->value],
            //             ],
            //         ],
            //         'quantity' => 1,
            //     ]],
            //     'mode' => 'payment',
            //     'success_url' => route('pay-stripe.success', ['callback' => $callback, 'transaction_reference' => $tran]),
            //     'cancel_url' => url()->previous(),
            // ]);
            // return response()->json(['payment_link' => $checkout_session->url]);
        } catch (ApiErrorException $e) {
            // Log the error for debugging
            \Log::error('Stripe API Error: ' . $e->getMessage());

            // Handle error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }


    public function payment_process_3d(Request $request)
    {
        $tran = Str::random(6) . '-' . rand(1, 1000);
        $order_amount = $request['order_amount'];
        $callback = $request['callback'];
        $config = Helpers::get_business_settings('stripe');
        
        Stripe::setApiKey($config['api_key']);
        header('Content-Type: application/json');
        $currency_code = Helpers::get_business_settings('currency');

        $checkout_session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [[
                'price_data' => [
                    'currency' => $currency_code ?? 'usd',
                    'unit_amount' => $order_amount * 100,
                    'product_data' => [
                        'name' => BusinessSetting::where(['key' => 'restaurant_name'])->first()->value,
                        'images' => [asset('storage/app/public/restaurant') . '/' . BusinessSetting::where(['key' => 'logo'])->first()->value],
                    ],
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('pay-stripe.success', ['callback' => $callback, 'transaction_reference' => $tran]),
            'cancel_url' => url()->previous(),
        ]);
        return response()->json(['id' => $checkout_session->id]);
    }

    public function success(Request $request)
    {
        $callback = $request['callback'];

        //token string generate
        $transaction_reference = $request['transaction_reference'];
        $token_string = 'payment_method=stripe&&transaction_reference=' . $transaction_reference;

        //success
        if ($callback != null) {
            return redirect($callback . '/success' . '?token=' . base64_encode($token_string));
        } else {
            return \redirect()->route('payment-success', ['token' => base64_encode($token_string)]);
        }
    }

    public function fail(Request $request)
    {
        $callback = $request['callback'];

        //token string generate
        $transaction_reference = $request['transaction_reference'];
        $token_string = 'payment_method=stripe&&transaction_reference=' . $transaction_reference;

        //fail
        if ($callback != null) {
            return redirect($callback . '/fail' . '?token=' . base64_encode($token_string));
        } else {
            return \redirect()->route('payment-fail', ['token' => base64_encode($token_string)]);
        }
    }
}

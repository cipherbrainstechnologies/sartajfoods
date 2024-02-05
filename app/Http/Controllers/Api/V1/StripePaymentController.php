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
use Illuminate\Support\Str;
use Stripe\Charge;
use Stripe\Stripe;
use Stripe\PaymentIntent;

class StripePaymentController extends Controller
{
    public function payment_process_3d(Request $request)
    {
        $tran = Str::random(6) . '-' . rand(1, 1000);
        $order_amount = $request['order_amount'];
        $callback = $request['callback'];
        $config = Helpers::get_business_settings('stripe');
        
        Stripe::setApiKey($config['api_key']);
        header('Content-Type: application/json');
        $currency_code = Helpers::get_business_settings('currency');

        // $checkout_session = \Stripe\Checkout\Session::create([
        //     'payment_method_types' => ['card'],
        //     'line_items' => [[
        //         'price_data' => [
        //             'currency' => $currency_code ?? 'usd',
        //             'unit_amount' => $order_amount * 100,
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
        $orderAmount = 1000; // in cents
    $currencyCode = 'usd';

    // Create a Checkout Session
    $session = Session::create([
        'payment_method_types' => ['card'],
        'line_items' => [[
            'price_data' => [
                'currency' => $currencyCode,
                'unit_amount' => $orderAmount,
                'product_data' => [
                    'name' => 'Your Product Name',
                ],
            ],
            'quantity' => 1,
        ]],
        'mode' => 'payment',
        'success_url' => route('pay-stripe.success', ['callback' => $request->callback, 'transaction_reference' => $tran]),
        'cancel_url' => url()->previous(),
    ]);

    // Retrieve the URL for the Checkout Session
    $paymentLinkUrl = $session->url;

    // Redirect the user to the Payment Link URL
    return redirect()->away($paymentLinkUrl);
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

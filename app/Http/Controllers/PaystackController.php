<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Model\Order;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;

use App\Http\Requests;
use Illuminate\Support\Facades\Config;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Paystack;

class PaystackController extends Controller
{
    public function __construct()
    {
        //configuration initialization
        $paystack = Helpers::get_business_settings('paystack');
        if ($paystack) {
            $config = array(
                'publicKey' => env('PAYSTACK_PUBLIC_KEY', $paystack['publicKey']),
                'secretKey' => env('PAYSTACK_SECRET_KEY', $paystack['secretKey']),
                'paymentUrl' => env('PAYSTACK_PAYMENT_URL', $paystack['paymentUrl']),
                'merchantEmail' => env('MERCHANT_EMAIL', $paystack['merchantEmail']),
            );
            Config::set('paystack', $config);
        }
    }
    public function redirectToGateway(Request $request)
    {
        \session()->put('callback', $request['callback']);
        try {
            return Paystack::getAuthorizationUrl()->redirectNow();

        } catch (\Exception $e) {
            Toastr::error(translate('Your currency is not supported by Paystack.'));
            return Redirect::back()->withErrors(['error' => 'Failed']);
        }
    }

    public function handleGatewayCallback()
    {
        $callback = session('callback');

        $paymentDetails = Paystack::getPaymentData();

        //token string generate
        $transaction_reference = $paymentDetails['data']['reference'];
        $token_string = 'payment_method=paystack&&transaction_reference=' . $transaction_reference;

        if ($paymentDetails['status'] == true) {
            //success
            if ($callback != null) {
                return redirect($callback . '/success' . '?token=' . base64_encode($token_string));
            } else {
                return \redirect()->route('payment-success', ['token' => base64_encode($token_string)]);
            }

        } else {
            //fail
            if ($callback != null) {
                return redirect($callback . '/fail' . '?token=' . base64_encode($token_string));
            } else {
                return \redirect()->route('payment-fail', ['token' => base64_encode($token_string)]);
            }
        }
    }

    public static function generate_transaction_Referance()
    {

        return Paystack::genTranxRef();
    }
}

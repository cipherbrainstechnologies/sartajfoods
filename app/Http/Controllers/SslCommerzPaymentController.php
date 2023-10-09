<?php

namespace App\Http\Controllers;

use App\CentralLogics\Helpers;
use App\Library\SslCommerz\SslCommerzNotification;
use App\Model\Order;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Config;
use App\User;

use DGvai\SSLCommerz\SSLCommerz;


class SslCommerzPaymentController extends Controller
{

    public function __construct()
    {
        $mode = env('APP_MODE');
        $ssl = Helpers::get_business_settings('ssl_commerz_payment');
        if ($ssl) {
            if ($mode == 'live') {
                $url = "https://securepay.sslcommerz.com";
                $host = false;
            }else{
                $url = "https://sandbox.sslcommerz.com";
                $host = true;
            }

            //configuration initialization
            $config = array(
                'product_profile' => 'example',
                //'localhost' => false,
                'path' => [
                    'domain' => [
                        'sandbox' => 'https://sandbox.sslcommerz.com',
                        'live' => 'https://securepay.sslcommerz.com',
                    ],
                    'endpoint' => [
                        'make_payment' => $url . "/gwprocess/v4/api.php",
                        'transaction_status' => $url . "/validator/api/merchantTransIDvalidationAPI.php",
                        'order_validate' => $url . "/validator/api/validationserverAPI.php",
                        'refund_payment' => $url . "/validator/api/merchantTransIDvalidationAPI.php",
                        'refund_status' => $url . "/validator/api/merchantTransIDvalidationAPI.php",
                    ]

                ],
                'store' => [
                    'id' => $ssl['store_id'],
                    'password' => $ssl['store_password'],
                ],
                'route' => [
                    'success' => 'ssl-success',
                    'failure' => 'ssl-failure',
                    'cancel' => 'ssl-cancel',
                    'ipn' => 'ssl-ipn',
                ]
            );
            Config::set('sslcommerz', $config);
        }
    }
    public function index(Request $request)
    {
        //initialization
        $callback = $request['callback'];
        $customer = User::find($request['customer_id']);
        $order_amount = $request['order_amount'];
        $transaction_reference = Str::random(6) . '-' . rand(1, 1000);

        try {
            $sslc = new SSLCommerz();
            $sslc->amount($order_amount)
                ->trxid($transaction_reference)
                ->product('Product')
                ->customer($customer['f_name'] . ' ' . $customer['l_name'] ,$customer['mail']??'example@example.com')
                ->setUrl([route('ssl-success', ['callback'=>$callback]), route('ssl-failure', ['callback'=>$callback]), route('ssl-cancel', ['callback'=>$callback]), route('ssl-ipn') ])
                ->setCurrency(Helpers::currency_code());

            $payment_options = $sslc->make_payment();
            if (!SSLCommerz::query_transaction($transaction_reference)->status) {
                Toastr::error('Your currency is not supported by SSLCOMMERZ.');
                return back()->withErrors(['error' => 'Failed']);
            }

            return $payment_options;

        } catch (\Exception $exception) {
            Toastr::error('Misconfiguration or data is missing!');
            return back()->withErrors(['error' => 'Failed']);
        }
    }

    public function success(Request $request)
    {
        //token string generate
        $callback = $request['callback'];
        $token_string = 'payment_method=ssl_commerz_payment&&transaction_reference=' . $request['tran_id'];

        //success
        if ($callback != null) {
            return redirect($callback . '/success' . '?token=' . base64_encode($token_string));
        } else {
            return \redirect()->route('payment-success', ['token' => base64_encode($token_string)]);
        }
    }

    public function fail(Request $request)
    {
         //token string generate
         $callback = $request['callback'];
         $token_string = 'payment_method=ssl_commerz_payment&&transaction_reference=' . $request['tran_id'];
 
         //fail
         if ($callback != null) {
             return redirect($callback . '/fail' . '?token=' . base64_encode($token_string));
         } else {
             return \redirect()->route('payment-fail', ['token' => base64_encode($token_string)]);
         }
    }

    public function cancel(Request $request)
    {
        //token string generate
        $callback = $request['callback'];
        $token_string = 'payment_method=ssl_commerz_payment&&transaction_reference=' . $request['tran_id'];

        //cancel
        if ($callback != null) {
            return redirect($callback . '/cancel' . '?token=' . base64_encode($token_string));
        } else {
            return \redirect()->route('payment-cancel', ['token' => base64_encode($token_string)]);
        }
    }

    public function ipn(Request $request)
    {
        #Received all the payement information from the gateway
        if ($request->input('tran_id')) #Check transation id is posted or not.
        {
            $tran_id = $request->input('tran_id');
            #Check order status in order tabel against the transaction id or order id.
            $order_details = DB::table('orders')
                ->where('transaction_reference', $tran_id)
                ->select('transaction_reference', 'order_status', 'order_amount')->first();

            if ($order_details->order_status == 'pending') {
                $sslc = new SslCommerzNotification();
                $validation = $sslc->orderValidate($tran_id, $order_details->order_amount, 'BDT', $request->all());
                if ($validation == TRUE) {
                    /*
                    That means IPN worked. Here you need to update order status
                    in order table as confirmed or Complete.
                    Here you can also sent sms or email for successful transaction to customer
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_reference', $tran_id)
                        ->update(['order_status' => 'confirmed', 'payment_status' => 'paid']);

                    echo "Transaction is successfully completed";
                } else {
                    /*
                    That means IPN worked, but Transation validation failed.
                    Here you need to update order status as Failed in order table.
                    */
                    $update_product = DB::table('orders')
                        ->where('transaction_reference', $tran_id)
                        ->update(['order_status' => 'confirmed', 'payment_status' => 'unpaid']);

                    echo "validation Fail";
                }

            } else if ($order_details->order_status == 'confirmed' || $order_details->order_status == 'complete') {

                #That means Order status already updated. No need to udate database.

                echo "Transaction is already successfully completed";
            } else {
                #That means something wrong happened. You can redirect customer to your product page.

                echo "Invalid Transaction";
            }
        } else {
            echo "Invalid Data";
        }
    }

}

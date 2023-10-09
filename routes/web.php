<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
 */

/*Route::get('/', function () {
return view('welcome');
});

Auth::routes();

*/


Route::get('/', function () {
    return redirect(\route('admin.dashboard'));
});

Route::get('authentication-failed', function () {
    $errors = [];
    array_push($errors, ['code' => 'auth-001', 'message' => 'Unauthenticated.']);
    return response()->json([
        'errors' => $errors,
    ], 401);
})->name('authentication-failed');

Route::group(['prefix' => 'payment-mobile'], function () {
    Route::get('/', 'PaymentController@payment')->name('payment-mobile');
    Route::get('set-payment-method/{name}', 'PaymentController@set_payment_method')->name('set-payment-method');
});

//SSLCOMMERZ Start
Route::post('sslcommerz/pay', 'SslCommerzPaymentController@index')->name('pay-ssl');
Route::post('sslcommerz/success','SslCommerzPaymentController@success')->name('ssl-success');
Route::post('sslcommerz/failure','SslCommerzPaymentController@fail')->name('ssl-failure');
Route::post('sslcommerz/cancel','SslCommerzPaymentController@cancel')->name('ssl-cancel');
Route::post('sslcommerz/ipn','SslCommerzPaymentController@ipn')->name('ssl-ipn');
//SSLCOMMERZ END

/*paypal*/
/*Route::get('/paypal', function (){return view('paypal-test');})->name('paypal');*/
Route::post('pay-paypal', 'PaypalPaymentController@payWithpaypal')->name('pay-paypal');
Route::get('paypal-status', 'PaypalPaymentController@getPaymentStatus')->name('paypal-status');
/*paypal*/

/*Route::get('stripe', function (){
return view('stripe-test');
});*/
Route::get('pay-stripe', 'StripePaymentController@payment_process_3d')->name('pay-stripe');
Route::get('pay-stripe/success', 'StripePaymentController@success')->name('pay-stripe.success');
Route::get('pay-stripe/fail', 'StripePaymentController@success')->name('pay-stripe.fail');

// Get Route For Show Payment Form
Route::get('paywithrazorpay', 'RazorPayController@payWithRazorpay')->name('paywithrazorpay');
Route::post('payment-razor', 'RazorPayController@payment')->name('payment-razor');

/*Route::fallback(function () {
return redirect('/admin/auth/login');
});*/

Route::get('payment-success', 'PaymentController@success')->name('payment-success');
Route::get('payment-fail', 'PaymentController@fail')->name('payment-fail');

//senang pay
Route::match(['get', 'post'], '/return-senang-pay', 'SenangPayController@return_senang_pay')->name('return-senang-pay');


//paystack
Route::post('/paystack-pay', 'PaystackController@redirectToGateway')->name('paystack-pay');
Route::get('/paystack-callback', 'PaystackController@handleGatewayCallback')->name('paystack-callback');
Route::get('/paystack',function (){
    return view('paystack');
});

//bkash
Route::group(['prefix'=>'bkash'], function () {
    // Payment Routes for bKash
//    Route::post('get-token', 'BkashPaymentController@getToken')->name('bkash-get-token');
//    Route::post('create-payment', 'BkashPaymentController@createPayment')->name('bkash-create-payment');
//    Route::post('execute-payment', 'BkashPaymentController@executePayment')->name('bkash-execute-payment');
//    Route::get('query-payment', 'BkashPaymentController@queryPayment')->name('bkash-query-payment');
//    Route::post('success', 'BkashPaymentController@bkashSuccess')->name('bkash-success');

    Route::get('make-payment', 'BkashPaymentController@make_tokenize_payment')->name('bkash.make-payment');
    Route::any('callback', 'BkashPaymentController@callback')->name('bkash.callback');

    // Refund Routes for bKash
    Route::get('refund', 'BkashRefundController@index')->name('bkash-refund');
    Route::post('refund', 'BkashRefundController@refund')->name('bkash-refund');
});

// paymob
Route::post('/paymob-credit', 'PaymobController@credit')->name('paymob-credit');
Route::get('/paymob-callback', 'PaymobController@callback')->name('paymob-callback');

// The callback url after a payment
Route::get('mercadopago/home', 'MercadoPagoController@index')->name('mercadopago.index');
Route::post('mercadopago/make-payment', 'MercadoPagoController@make_payment')->name('mercadopago.make_payment');
Route::get('mercadopago/get-user', 'MercadoPagoController@get_test_user')->name('mercadopago.get-user');

// The route that the button calls to initialize payment
Route::post('/flutterwave-pay','FlutterwaveController@initialize')->name('flutterwave_pay');
// The callback url after a payment
Route::get('/rave/callback', 'FlutterwaveController@callback')->name('flutterwave_callback');

Route::get('add-currency', function () {
    $currencies = file_get_contents("installation/currency.json");
    $decoded = json_decode($currencies, true);
    $keep = [];
    foreach ($decoded as $item) {
        array_push($keep, [
            'country'         => $item['name'],
            'currency_code'   => $item['code'],
            'currency_symbol' => $item['symbol_native'],
            'exchange_rate'   => 1,
        ]);
    }
    DB::table('currencies')->insert($keep);
    return response()->json(['ok']);
});

Route::get('/test', function () {
    \App\CentralLogics\Helpers::setEnvironmentValue('fff','123');
    return 0;
});

Route::any('6cash/make-payment', 'SixCashPaymentController@make_payment')->name('6cash.make-payment');
Route::any('6cash/callback','SixCashPaymentController@callback')->name('6cash.callback');

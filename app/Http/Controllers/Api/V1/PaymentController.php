<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Order;

class PaymentController extends Controller
{
    public function payment(Request $request)
    {
        if (session()->has('payment_method') == false) {
            session()->put('payment_method', 'ssl_commerz_payment');
        }

        $params = explode('&&', base64_decode($request['token']));

        foreach ($params as $param) {
            $data = explode('=', $param);
            if ($data[0] == 'customer_id') {
                session()->put('customer_id', $data[1]);
            } elseif ($data[0] == 'callback') {
                session()->put('callback', $data[1]);
            } elseif ($data[0] == 'order_amount') {
                session()->put('order_amount', $data[1]);
            } elseif ($data[0] == 'product_ids') {
                session()->put('product_ids', $data[1]);
            }
        }

        $customer = User::find(session('customer_id'));
        $order_amount = session('order_amount');

        if (isset($customer) && isset($order_amount)) {
            $data = [
                'name' => $customer['f_name'],
                'email' => $customer['email'],
                'phone' => $customer['phone'],
            ];
            session()->put('data', $data);
            return view('payment-view', ['payment_method' => $request['payment_method']]);
        }

        if (!isset($customer))
            return response()->json(['errors' => ['message' => 'Customer not found']], 403);
        elseif (!isset($order_amount))
            return response()->json(['errors' => ['message' => 'Amount not found']], 403);
        else
            return response()->json(['errors' => ['message' => '']], 403);

    }

    public function success()
    {
        if (session()->has('callback')) {
            return redirect(session('callback') . '/success');
        }
        return response()->json(['message' => 'Payment succeeded'], 200);
    }

    public function fail(Request $request)
    {
        $queryParams = $request->query();
        // Alternatively, you can get individual parameters
        $callback = $request->query('callback');
        $transactionReference = $request->query('transaction_reference');
        // $payment_id = $request->query('paymentId');
        // $token = $request->query('token');
        // $payerId = $request->query('PayerID');
        $orderId = $request->query('order_id');
        $customer = $request->query('customer');

        $order = Order::find($orderId);
        $order->transaction_reference = $transactionReference;
        $order->order_status= "pending";
        $order->payment_status = "unpaid";
        $order->save();
        return redirect($callback . '/fail'.'?order_id=' .$orderId .'&name='.$customer);




    }
}

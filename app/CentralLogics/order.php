<?php

namespace App\CentralLogics;

use App\Model\Order;
use App\Model\Product;
use App\Model\OrderHistory;
use App\Model\BrowserHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderLogic
{
    public static function track_order($order_id,$user_id)
    {
        return Order::with(['details', 'delivery_man.rating'])->where(['id' => $order_id,'user_id' => $user_id])->first();
    }

    public static function place_order($customer_id, $email, $customer_info, $cart, $payment_method, $discount, $coupon_code = null)
    {
        try {
            $or = [
                'id' => 100000 + Order::all()->count() + 1,
                'user_id' => $customer_id,
                'order_amount' => CartManager::cart_grand_total($cart) - $discount,
                'payment_status' => 'unpaid',
                'order_status' => 'pending',
                'payment_method' => $payment_method,
                'transaction_ref' => null,
                'discount_amount' => $discount,
                'coupon_code' => $coupon_code,
                'discount_type' => $discount == 0 ? null : 'coupon_discount',
                'shipping_address' => $customer_info['address_id'],
                'created_at' => now(),
                'updated_at' => now()
            ];

            $o_id = DB::table('orders')->insertGetId($or);

            foreach ($cart as $c) {
                $product = Product::where('id', $c['id'])->first();
                $or_d = [
                    'order_id' => $o_id,
                    'product_id' => $c['id'],
                    'seller_id' => $product->added_by == 'seller' ? $product->user_id : '0',
                    'product_details' => $product,
                    'qty' => $c['quantity'],
                    'price' => $c['price'],
                    'tax' => $c['tax'] * $c['quantity'],
                    'discount' => $c['discount'] * $c['quantity'],
                    'discount_type' => 'discount_on_product',
                    'variant' => $c['variant'],
                    'variation' => json_encode($c['variations']),
                    'delivery_status' => 'pending',
                    'shipping_method_id' => $c['shipping_method_id'],
                    'payment_status' => 'unpaid',
                    'created_at' => now(),
                    'updated_at' => now()
                ];
                DB::table('order_details')->insert($or_d);
            }

            $emailServices = Helpers::get_business_settings('mail_config');

            if (isset($emailServices['status']) && $emailServices['status'] == 1) {
                Mail::to($email)->send(new \App\Mail\OrderPlaced($o_id));
            }
            
        } catch (\Exception $e) {

        }

        return $o_id;
    }

    public static function orderHistory($order_id, $status,$comment=null)
    {
        // $orderHistoryData = OrderHistory::with('order')->where('order_id', $request->id)->first();
       try {
            $data = [
                'order_id' => $order_id,
                'status' => $status,
                'comment' => $comment,
                'is_customer_notify' => 0, 
            ];

            $history = OrderHistory::create($data)->order();
       } catch (\Exception $e){
        return response()->json([
            'message' => 'Something want to wrong',
        ], 403);
       }
    }

    public static function browserHistory($user_id,$ip_address,$forwarded_ip,$user_agent,$accept_language){
        $browserHistory = BrowserHistory::updateOrCreate(
            [
                "user_id" => !empty($user_id) ? $user_id : null
            ],
            [
                "ip_address" => !empty($ip_address) ? $ip_address : null,
                "forwarded_ip" => !empty($forwarded_ip) ? $forwarded_ip : null,
                "user_agent" => !empty($user_agent) ? $user_agent : null,
                "accept_language" => !empty($accept_language) ? $accept_language : null
            ]
        );
        return $browserHistory;
        
    }


}

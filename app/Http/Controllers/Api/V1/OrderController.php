<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\CustomerLogic;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\CentralLogics\CouponLogic;
use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Model\CustomerAddress;
use App\Model\DMReview;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Cart;
use App\Model\Product;
use App\Model\Review;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use function App\CentralLogics\translate;
use PDF;
use DateTime;
use App\Model\BusinessSetting;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use App\Model\OrderHistory;
use App\CentralLogics\PaypalLogic;
use App\Http\Controllers\Api\V1\PaypalPaymentController;
use App\Http\Controllers\Api\V1\StripePaymentController;

class OrderController extends Controller
{
    private $paypal;
    public function __construct(
        private Coupon $coupon,
        private CustomerAddress $customer_address,
        private DMReview $dm_review,
        private Order $order,
        private OrderDetail $order_detail,
        private Product $product,
        private Review $review,
        private User $user,
        private BusinessSetting $business_setting,
        PaypalPaymentController $paypal,
        StripePaymentController $stripe
    ){
        $this->paypal = $paypal;
        $this->stripe = $stripe;
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function track_order(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        return response()->json(OrderLogic::track_order($request['order_id'],$request->user()->id), 200);
    }

    
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function place_order(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_amount' => 'required',
            'payment_method'=>'required',
            'delivery_address_id' => 'required',
            // 'order_type' => 'required|in:self_pickup,delivery',
            // 'branch_id' => 'required',
            // 'distance' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        if($request->payment_method == 'wallet_payment' && Helpers::get_business_settings('wallet_status', false) != 1)
        {
            return response()->json([
                'errors' => [
                    ['code' => 'payment_method', 'message' => translate('customer_wallet_status_is_disable')]
                ]
            ], 203);
        }

        $customer = $this->user->find($request->user()->id);
        $coupon_discount = 0;
        $discountPrice = 0;

        if($request->payment_method == 'wallet_payment' && $customer->wallet_balance < $request['order_amount'])
        {
            return response()->json([
                'errors' => [
                    ['code' => 'payment_method', 'message' => translate('you_do_not_have_sufficient_balance_in_wallet')]
                ]
            ], 203);
        }

        $max_amount = Helpers::get_business_settings('maximum_amount_for_cod_order');

        if ($request->payment_method == 'cash_on_delivery' && Helpers::get_business_settings('maximum_amount_for_cod_order_status') == 1 && ($max_amount < $request['order_amount'])){
            $errors = [];
            $errors[] = ['code' => 'auth-001', 'message' => 'For Cash on Delivery, order amount must be equal or less than '. $max_amount];
            return response()->json([
                'errors' => $errors
            ], 401);
        }
        
        $min_amount = Helpers::get_business_settings('minimum_amount_for_cod_order');
        
        if ($request->payment_method == 'cash_on_delivery' && Helpers::get_business_settings('minimum_amount_for_cod_order_status') == 1 && ($request['order_amount'] < $min_amount)){
            $errors = [];
            $errors[] = ['code' => 'auth-001', 'message' => 'For Cash on Delivery, order amount must be equal or greater than '. $min_amount];
            return response()->json([
                'errors' => $errors
            ], 401);
        }

        foreach ($request['cart'] as $c) {
            
            // $product = $this->product->find($c['product_id']);
            $product = $this->product->find($c['id']);
            // $type = $c['variation'][0]['type'];

            $order_qty = $c['quantity'];
           
            if ($order_qty > $c['product']['total_stock'] ) {
                return response()->json([
                    'message' => 'Stock is insufficient! available stock ' . $c['product']['total_stock'],
                ], 404);
            }
            
            // $type = $c['variations'][0]['type'];
            // foreach (json_decode($product['variations'], true) as $var) {
            //     if ($type == $var['type'] && $var['stock'] < $c['quantity']) {
            //         $validator->getMessageBag()->add('stock', 'Stock is insufficient! available stock ' . $var['stock']);
            //     }
            // }
        }
        $free_delivery_amount = 0;
        if ($request['order_type'] == 'self_pickup'){
            $delivery_charge = 0;
        } elseif (Helpers::get_business_settings('free_delivery_over_amount_status') == 1 && (Helpers::get_business_settings('free_delivery_over_amount') <= $request['order_amount'])){
            $delivery_charge = 0;
            $free_delivery_amount = Helpers::get_delivery_charge($request['distance']);
        } else{
            $delivery_charge = Helpers::get_delivery_charge($request['distance']);
        }

        if(!empty($request['coupon_code'])){
            $coupon = $this->coupon->active()->where(['code' => $request['coupon_code']])->first();
            if (isset($coupon)) {
                if($coupon['start_date'] <= now() && $coupon['expire_date'] >= now()){
                    if ($coupon['coupon_type'] == 'free_delivery') {
                        $free_delivery_amount = Helpers::get_delivery_charge($request['distance']);
                        $discountPrice = 0;
                        $delivery_charge = 0;
                    } else {
                        if($coupon['discount_type'] == "percent"){
                            $discountPrice = ($request['order_amount'] *  $coupon['discount'])/100;
                            if($discountPrice > $coupon['max_discount']){
                                if($request['order_amount'] > $coupon['min_purchase']){
                                    $discountPrice = $coupon['max_discount'];
                                }else{
                                    $errors[] = ['code' => 'auth-001', 'message' => 'order amount is less than minimum purchase amount'];
                                    return response()->json([
                                        'errors' => $errors
                                    ], 401);
                                } 
                            }
                        }else{
                            $discountPrice = $coupon['discount'];
                            if($request['order_amount'] > $coupon['min_purchase']){
                                $discountPrice = $coupon['max_discount'];
                            }else{
                                $errors[] = ['code' => 'auth-001', 'message' => 'order amount is less than minimum purchase amount'];
                                return response()->json([
                                    'errors' => $errors
                                ], 401);
                            } 
                        }
                        $coupon_discount = !empty($request['coupon_discount_amount']) ? $request['coupon_discount_amount'] : 0;
                    }
                }else{
                    $errors[] = ['code' => 'auth-001', 'message' => 'coupon code is expired'];
                    return response()->json([
                        'errors' => $errors
                    ], 401);
                }
            }else{
                $errors = [];
                $errors[] = ['code' => 'auth-001', 'message' => 'coupon code not valid'];
                return response()->json([
                    'errors' => $errors
                ], 401);
            }
        }
        $browserHistory = OrderLogic::browserHistory($request->user()->id,$request->ip_address,$request->forwarded_ip,$request->user_agent,$request->accept_language);
        // try {
            //DB::beginTransaction();
            $order_id = 100000 + Order::all()->count() + 1;
            $or = [
                'id' => $order_id,
                'user_id' => $request->user()->id,
                'browser_history_id' => !empty($browserHistory) ? $browserHistory->id : 0,
                'order_amount' => $request['order_amount'],
                'coupon_code' =>  $request['coupon_code'],
                //'coupon_discount_amount' => $coupon_discount_amount,
                'coupon_discount_amount' => $request->coupon_discount_amount,
                'coupon_discount_title' => $request->coupon_discount_title == 0 ? null : 'coupon_discount_title',
                'payment_status' => ($request->payment_method=='cash_on_delivery' || $request->payment_method=='offline_payment')?'unpaid':'paid',
                'order_status' => ($request->payment_method=='cash_on_delivery' || $request->payment_method=='offline_payment')?'pending':'confirmed',
                'payment_method' => $request->payment_method,
                'transaction_reference' => $request->transaction_reference ?? null,
                'order_note' => !empty($request['order_note']) ? $request['order_note'] : null,
                'order_type' => $request['order_type'],
                'branch_id' => !empty($request['branch_id']) ? $request['branch_id'] : null,
                'delivery_address_id' => $request->delivery_address_id,
                'time_slot_id' => !empty($request->time_slot_id) ? $request->time_slot_id : null, 
                'delivery_date' => $request->delivery_date,
                'delivery_address' => json_encode(CustomerAddress::find($request->delivery_address_id) ?? null),
                'date' => date('Y-m-d'),
                'delivery_charge' => $delivery_charge,
                'payment_by' => $request['payment_method'] == 'offline_payment' ? $request['payment_by'] : null,
                'payment_note' => $request['payment_method'] == 'offline_payment' ? $request['payment_note'] : null,
                'free_delivery_amount' => $free_delivery_amount,
                'created_at' => now(),
                'updated_at' => now(),
            ];
            $o_time = $or['time_slot_id'];
            $o_delivery = $or['delivery_date'];

            $total_tax_amount = 0;

            foreach ($request['cart'] as $c) {
                
                $product = $this->product->find($c['product_id']);
                // if ($product['maximum_order_quantity'] < $c['quantity']){
                //     return response()->json(['errors' => $product['name']. ' '. \App\CentralLogics\translate('quantity_must_be_equal_or_less_than '. $product['maximum_order_quantity'])], 401);
                // }
               
                if (count(json_decode($product['variations'], true)) > 0) {
                    // $price = Helpers::variation_price($product, json_encode($c['variation']));
                    $price = Helpers::variation_price($product, json_encode($c['variations']));
                } else {
                    $price =$product['actual_price'];
                    // if(!empty($product->sale_price) && $product->sale_start_date <= now() && $product->sale_end_date >= now()){
                    //     $price = $product['sale_price'];
                    // }else{
                    //     $price = $product['price'];
                    // }
                    
                }
                
                // $tax_on_product = Helpers::tax_calculate($product, $price);

                $calculateTaxes = Helpers::tax_calculates($product,$price);
                $discount = Helpers::afterDiscountPrice($product,$price);
                
                
                    //                if (Helpers::get_business_settings('product_vat_tax_status') === 'included'){
                    //                    //$price = $price - $tax_on_product;
                    //                }

                $category_id = null;
                // foreach (json_decode($product['category_ids'], true) as $cat) {
                foreach ($product['category_ids'] as $cat) {
                    if ($cat['position'] == 1){
                        $category_id = ($cat['id']);
                    }
                }

                // $category_discount = Helpers::category_discount_calculate($category_id, $price);
                // $product_discount = Helpers::discount_calculate($product, $price);
                // if ($category_discount >= $price){
                //     $discount = $product_discount;
                //     $discount_type = 'discount_on_product';
                // }else{
                //     $discount = max($category_discount, $product_discount);
                //     $discount_type = $product_discount > $category_discount ? 'discount_on_product' : 'discount_on_category';
                // }
                $or_d = [
                    'order_id' => $order_id,
                    'product_id' => $c['product_id'],
                    // 'product_id' => $c['id'],
                    'time_slot_id' => $o_time,
                    'delivery_date' => $o_delivery,
                    'product_details' => $product,
                    'quantity' => $c['quantity'],
                    'price' => $price,
                    'unit' => $product['unit'],
                    'tax_amount' => !empty($calculateTaxes['eight_percent']) ? ($calculateTaxes['eight_percent'] * $c['quantity']) : ($calculateTaxes['ten_percent'] * $c['quantity']),
                    'eight_percent_tax'=>($calculateTaxes['eight_percent'] * $c['quantity']),
                    'ten_percent_tax' => ($calculateTaxes['ten_percent'] * $c['quantity']),
                    'discount_on_product' => $discount['discount_amount'],
                    'total_discount' => ($discount['discount_amount'] * $c['quantity']),
                    'discount_type' => $discount['discount_type'],
                    // 'variant' => json_encode($c['variant']),
                    // 'variation' => json_encode($c['variation']),
                    // 'variation' => json_encode($c['variations']),
                    'is_stock_decreased' => 1,
                    'vat_status' => Helpers::get_business_settings('product_vat_tax_status') === 'included' ? 'included' : 'excluded',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
                $total_tax_amount += $or_d['tax_amount'] * $c['quantity'];

                // $type = $c['variation'][0]['type'];
                // $type = $c['variations'][0]['type'];
               
                $var_store = [];
                foreach (json_decode($product['variations'], true) as $var) {
                    if ($type == $var['type']) {
                        $var['stock'] -= $c['quantity'];
                    }
                    $var_store[] = $var;
                }
                
                $this->product->where(['id' => $product['id']])->update([
                    'variations' => json_encode($var_store),
                    'total_stock' => $product['total_stock'] - $c['quantity'],
                    'out_of_stock_status' => ($product['total_stock'] - $c['quantity']==0) ? "out of stock": "in stock",
                    'popularity_count'=>$product['popularity_count']+1,
                    'maximum_order_quantity' => (($product['total_stock'] - $c['quantity']) > $product['maximum_order_quantity']) ? $product['maximum_order_quantity'] : $product['total_stock'] - $c['quantity']
                ]);
                DB::table('order_details')->insert($or_d);
                
                Cart::where('user_id',$request->user()->id)->delete();
            }
            Helpers::addRecentActivity($request->user(),"order_place");
            $or['total_tax_amount'] = $total_tax_amount;
            $latestOrder =DB::table('orders')->insertGetId($or);
            $o_status = ($request->payment_method=='cash_on_delivery' || $request->payment_method=='offline_payment')?'pending':'confirmed';
            OrderLogic::orderHistory($order_id, $o_status,$request->order_note);
            if($request->payment_method == 'wallet_payment'){
                $amount = $or['order_amount'];
                CustomerLogic::create_wallet_transaction($or['user_id'], $amount, 'order_place', $or['id']);
            }

            //push notification
            
            $fcm_token = $request->user()->cm_firebase_token;
            $order_status_message = $request->payment_method=='cash_on_delivery'?'pending':'confirmed';
            $value = Helpers::order_status_update_message($order_status_message);
            try {
                if ($value) {
                    $data = [
                        'title' => 'Order',
                        'description' => $value,
                        'order_id' => $order_id,
                        'image' => '',
                        'type' => 'order'
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                }

                //send email
                $emailServices = Helpers::get_business_settings('mail_config') ;

                if (isset($emailServices['status']) && $emailServices['status'] == 1) {
                    Mail::to($request->user()->email)->send(new \App\Mail\OrderPlaced($order_id));
                }

            } catch (\Exception $e) {
            }

            if($request->payment_method == "paypal"){
                $res = $this->paypal->payWithpaypal($request,$order_id);
                return response()->json(['payment_link'=>$res],200);
            }

            if($request->payment_method == "stripe"){
                return response()->json(["token"=>$this->stripe->createPaymentLink($request,$order_id)],200);
            }
            
            return response()->json([
                'message' => 'Order placed successfully!',
                'order_id' => $order_id,
            ], 200);

           // DB::commit();
        // } catch (\Exception $e) {
        //    // DB::rollBack();
        //     return response()->json([$e], 403);
        // }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_order_list(Request $request): \Illuminate\Http\JsonResponse
    {       
        $orders = $this->order->with(['customer', 'delivery_man.rating'])
            ->withCount('details')
            ->where(['user_id' => $request->user()->id])->get();
           
            // dd($pdf);

        $orders->map(function ($data) {
            $data['deliveryman_review_count'] = $this->dm_review->where(['delivery_man_id' => $data['delivery_man_id'], 'order_id' => $data['id']])->count();

            $order = $this->order->with('delivery_address','details')->where('id', $data['id'])->first();
            $orderDetails =collect($order->details);
            $EightPercentTax = $orderDetails->sum('eight_percent_tax');
            $TenPercentTax = $orderDetails->sum('ten_percent_tax');
                    
            $totalAmt = (Helpers::calculateInvoice($data['id'])) + $order['delivery_charge'];
            $footer_text = $this->business_setting->where(['key' => 'footer_text'])->first();

            
            $pdf = PDF::loadView('admin-views.order.latest_invoice', compact('order', 'footer_text','totalAmt','TenPercentTax','EightPercentTax'));
            $timestamp = $data['created_at']->timestamp;
            $pdfName = 'Invoice_' . ($timestamp+$data['id']) . '.pdf';
            if (!Storage::disk('public')->exists('invoices')) {
                Storage::disk('public')->makeDirectory('invoices');
            }
            
            $pdfPath = Storage::disk('public')->put('invoices/' . $pdfName, $pdf->output());
            $pdfUrl = asset('storage/invoices/' . $pdfName);
            $data['invoice_link'] = $pdfUrl;
            return $data;
        });

        return response()->json($orders->map(function ($data) {
            $data->details_count = (integer)$data->details_count;
            return $data;
        }), 200);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_order_details(Request $request): \Illuminate\Http\JsonResponse
    {

        $validator = Validator::make($request->all(), [
            'order_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }
        // echo $request['order_id'];die;
        $details = OrderDetail::with('product')->where('order_id',$request['order_id'])->first();
        return response()->json($details, 200);
        // $details = $this->order_detail->where(['order_id' => $request['order_id']])
        //     ->whereHas('order', function ($q) use ($request){
        //         $q->where([ 'user_id' => $request->user()->id ]);
        //     })
        //     ->orderBy('id', 'desc')
        //     ->get();
        // if ($details->count() > 0) {
        //     foreach ($details as $det) {
        //         $det['variation'] = json_decode($det['variation'], true);
        //         if ($this->order->find($request->order_id)->order_type == 'pos') {
        //             $det['variation'] = (string)implode('-', array_values($det['variation'])) ?? null;
        //         }
        //         else {
        //             $det['variation'] = (string)$det['variation'][0]['type']??null;
        //         }

        //         $det['review_count'] = $this->review->where(['order_id' => $det['order_id'], 'product_id' => $det['product_id']])->count();
        //         $product = $this->product->where('id', $det['product_id'])->first();
        //         $det['product_details'] = isset($product) ? Helpers::product_data_formatting($product) : null;
        //     }
        //     return response()->json($details, 200);
        // } else {
        //     return response()->json([
        //         'errors' => [
        //             ['code' => 'order', 'message' => 'Order not found!']
        //         ]
        //     ], 401);
        // }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function cancel_order(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($this->order->where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->first()) {
            $order = $this->order->with(['details'])->where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->first();
            foreach ($order->details as $detail) {
                if ($detail['is_stock_decreased'] == 1) {
                    $product = $this->product->find($detail['product_id']);
                    // $type = json_decode($detail['variation'])[0]->type;
                    // $var_store = [];
                    // foreach (json_decode($product['variations'], true) as $var) {
                    //     if ($type == $var['type']) {
                    //         $var['stock'] += $detail['quantity'];
                    //     }
                    //     $var_store[] = $var;
                    // }

                    $this->product->where(['id' => $product['id']])->update([
                        // 'variations' => json_encode($var_store),
                        'total_stock' => $product['total_stock'] + $detail['quantity'],
                    ]);

                    $this->order_detail->where(['id' => $detail['id']])->update([
                        'is_stock_decreased' => 0,
                    ]);
                }
            }
            $this->order->where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->update([
                'order_status' => 'canceled',
            ]);
            Helpers::addRecentActivity($request->user(),"canceled",$request['order_id']);
            return response()->json(['message' => 'Order canceled'], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => 'not found!'],
            ],
        ], 401);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_payment_method(Request $request): \Illuminate\Http\JsonResponse
    {
        if ($this->order->where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->first()) {
            $this->order->where(['user_id' => $request->user()->id, 'id' => $request['order_id']])->update([
                'payment_method' => $request['payment_method'],
            ]);
            return response()->json(['message' => 'Payment method is updated.'], 200);
        }
        return response()->json([
            'errors' => [
                ['code' => 'order', 'message' => 'not found!'],
            ],
        ], 401);
    }

    public function shipping_list($order_id)
    {
        $eight_percent = 0;
        $ten_percent = 0;
        $total_sub_amt = 0;
        $discount = 0;
        $order = $this->order->with('details.product','details')->where('id', $order_id)->first();
        if(!empty($order)){
            $order->delivery_address = (array)$order->delivery_address;
            $order_detail = $this->order_detail->where('order_id', $order_id)->get()->toArray();
            $ids = [];
            foreach($order_detail as $product) {
                
            $ids[] = $product['product_id'];
            $productDetails = json_decode($product['product_details'],true);

                if($productDetails['tax'] == 8){
                    if(!empty($product['sale_price']) && $product['sale_start_date'] <= now() && $product['sale_end_date'] >= now()){
                        $eight_percent += ((($product['sale_price'] * $product['tax']) / 100) * $product['quantity']);   
                    }else{
                        $discount_price = Helpers::afterDiscountPrice($productDetails,$product['price']);
                        $eight_percent += (((($product['price'] - $discount_price['discount_amount']) * $productDetails['tax']) / 100) * $product['quantity']);      
                    }
            
                }
                if($productDetails['tax'] == 10){
                    if(!empty($product['sale_price']) && $product['sale_start_date'] <= now() && $product['sale_end_date'] >= now()){
                        $ten_percent += ((($product['sale_price'] * $product['tax']) / 100) * $product['quantity']);   
                    }else{
                        $discount_price = Helpers::afterDiscountPrice($productDetails,$product['price']);
                        $ten_percent += (((($product['price'] - $discount_price['discount_amount']) * $productDetails['tax']) / 100) * $product['quantity']);   
                    }
                    
                }

                // $calculateTaxes = Helpers::tax_calculates($productDetails,$product['price']);
                // $eight_percent = $calculateTaxes['eight_percent'];
                // $ten_percent= $calculateTaxes['ten_percent'];
                
                if(!empty($productDetails['sale_price'])){
                    $currentDate = new DateTime(); // Current date and time
                    $saleStartDate = new DateTime($productDetails['sale_start_date']);
                    $saleEndDate = new DateTime($productDetails['sale_end_date']);
                    if($currentDate >= $saleStartDate && $currentDate <= $saleEndDate){
                        $productPrice = $productDetails['actual_price'];
                        $discount = 0;
                        $total_sub_amt = $total_sub_amt + $productDetails['actual_price'] * $product['quantity'];
                    }else{
                        $discount_price = Helpers::afterDiscountPrice($productDetails,$product['price']);
                        $total_sub_amt = $total_sub_amt + ((($productDetails['actual_price'] - $discount_price['discount_amount'] )*  $product['quantity']));
                    }   
                    
                }else{
                    $discount_price = Helpers::afterDiscountPrice($productDetails,$productDetails['actual_price']);
                    $discount = ($discount_price['discount_amount'] * $product['quantity']);
                    $total_sub_amt = $total_sub_amt + ((($productDetails['actual_price'] - $discount_price['discount_amount'] )*  $product['quantity']));
                }
                
            }
            
                $order->couponPrice = round($order->coupon_discount_amount ,2);
                $order->total_sub_amt = round($total_sub_amt,2);
                $order->total_amt = round(($total_sub_amt + $eight_percent +  $ten_percent + Helpers::get_business_settings('delivery_charge') - round($order->coupon_discount_amount ,2) ),2);
                $order->eight_percent =  round($eight_percent,2);
                $order->ten_percent =  round($ten_percent,2);
                
            if(!empty($ids)){
                $productData  = $this->product->whereIn('id',$ids)->get();
                $order->products = $productData;
            }else{
                $order->products = [];   
            }
        
            if(!empty($order)) {
                return response()->json(['data' => $order], 200);
            } else {
                return response()->json(['data' => []], 404);
            }
        }else{
            return response()->json(['data' => []], 404);
        }
        
    }
    
    public function purchased_product($product_id){
        $orderIds = $this->order->where('user_id', auth()->user()->id)->where('order_status','delivered')->get()->pluck('id')->toArray();         
        if(!empty($orderIds)){     
            $order_detail = $this->order_detail->where('product_id',$product_id)->whereIn('order_id',$orderIds)->get();      
            if($order_detail->isNotEmpty()){
                foreach($order_detail as $key => $orders){
                    if(empty($orders->product->active_reviews->toArray())){
                        return response()->json(['purchased' => true,'reviewed' => false], 200);
                    }else{
                        return response()->json(['purchased' => true,'reviewed' => true], 200);
                    }
                }
            }else{
                return response()->json(['purchased' => true,'reviewed' => false], 200);
            }
        }else{
            return response()->json(['purchased' => false,'reviewed' => false], 200);
        }
    }
}

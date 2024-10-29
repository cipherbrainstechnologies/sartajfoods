<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Model\Order;
use App\Model\Cart;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Model\Regions;

class CouponController extends Controller
{
    public function __construct(
        private Coupon $coupon,
        private Order $order
    ){}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $coupons = $this->coupon->where('status', 1)
                ->where('expire_date', '>=', now()->format('Y-m-d'))
                ->where(function($query) use ($request) {
                    $query->where('customer_id', $request->user()->id)
                        ->orWhere('customer_id', null);
                })
                ->get();
            //get cart details
            $validCoupons = [];
            $cartDetails = Cart::with('product')->where(['user_id'=>$request->user()->id])->get();
            $orderAmount =  $cartDetails->sum('sub_total') + $cartDetails->sum('eight_percent') + $cartDetails->sum('ten_percent');  
            foreach($coupons as $coupon) {
                if(!empty($coupon->min_purchase)) {
                    if($coupon->min_purchase <= $orderAmount && ($coupon->limit == null || $coupon->limit !=0)) {
                        $validCoupons[] = $coupon;
                    }
                }
            }
            
            return response()->json($validCoupons, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    public function apply(Request $request){
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        $region_id = !empty($request->region_id) ? $request->region_id : 1;
        $orderAmount = 0;
        $coupon = $this->coupon->active()->where(['code' => $request['code']])->first();
        $cartDetails = Cart::with('product')->where(['user_id'=>$request->user()->id])->get();
        $orderAmount =  $cartDetails->sum('sub_total') + $cartDetails->sum('eight_percent') + $cartDetails->sum('ten_percent');
        // dd($orderAmount);
        if(empty($cartDetails)){
            return response()->json([
                'errors' => [
                    ['code' => 'coupon', 'message' => 'cart is empty coupon not apply']
                ]
            ], 404);
        }
        // $deliveryCharge = Helpers::get_business_settings('delivery_charge');
        $deliveryCharge = $this->get_delivery_charge($cartDetails, $region_id);
        $user = auth()->user();
        $redeem_points = !empty($request->use_wallet) ? $user->wallet_balance : 0;

        if($redeem_points >= ($orderAmount + $deliveryCharge)) {
            return response()->json([
                'errors' => [
                    ['code' => 'coupon', 'message' => 'coupon not apply']
                ]
            ], 404);
        }
        if (isset($coupon)) {
            if ($coupon['coupon_type'] == 'free_delivery') {
                $free_delivery_amount = Helpers::get_delivery_charge($request['distance']);
                $discountPrice = 0;
                $delivery_charge = 0;
            } else {
                if($coupon['discount_type'] == "percent"){
                    $discountPrice = ($orderAmount *  $coupon['discount'])/100;
                    if($discountPrice > $coupon['max_discount']){
                        if($orderAmount > $coupon['min_purchase']){
                            $discountPrice = $coupon['max_discount'];
                            $coupon->discount_price = round($discountPrice,2);
                            $coupon->orderAmount = round(($orderAmount - $discountPrice) +$deliveryCharge - $redeem_points ,2);
                            
                            return response()->json($coupon, 200);
                        }else{
                            $errors[] = ['code' => 'auth-001', 'message' => 'order amount is less than minimum purchase amount'];
                            return response()->json([
                                'errors' => $errors
                            ], 401);
                        } 
                    }else{
                        $$discountPrice = $coupon->discount;
                        $coupon->discount_price = round($discountPrice,2);
                        $coupon->orderAmount = round(($orderAmount - $discountPrice) +$deliveryCharge -$redeem_points ,2);
                        return response()->json($coupon, 200);
                    }
                }else{
                    if($orderAmount > $coupon['min_purchase']){
                        $discountPrice = $coupon['discount'];
                        $coupon->discount_price = round($discountPrice,2);
                        $coupon->orderAmount = round(($orderAmount - $discountPrice) + $deliveryCharge - $redeem_points,2);
                        $is_remove = ($request->is_remove) ? $request->is_remove : 0;
                        if(!empty($request->is_remove)) {
                            $coupon->orderAmount = $coupon->orderAmount + $discountPrice;
                        }
                        return response()->json($coupon, 200);
                    }else{
                        $errors[] = ['code' => 'auth-001', 'message' => 'order amount is less than minimum purchase amount'];
                        return response()->json([
                            'errors' => $errors
                        ], 401);
                    } 
                }
                // $coupon_discount = !empty($request['coupon_discount_amount']) ? $request['coupon_discount_amount'] : 0;
            }
        }else {
            return response()->json([
                'errors' => [
                    ['code' => 'coupon', 'message' => 'not found!']
                ]
            ], 404);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    // public function apply(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'code' => 'required',
    //     ]);

    //     if ($validator->errors()->count()>0) {
    //         return response()->json(['errors' => Helpers::error_processor($validator)], 403);
    //     }

    //     try {
    //         $coupon = $this->coupon->active()->where(['code' => $request['code']])->first();
    //         if (isset($coupon)) {

    //             //default coupon type
    //             if ($coupon['coupon_type'] == 'default') {
    //                 $total = $this->order->where(['user_id' => $request->user()->id, 'coupon_code' => $request['code']])->count();
    //                 if ($total < $coupon['limit']) {
    //                     return response()->json($coupon, 200);
    //                 }else{
    //                     return response()->json([
    //                         'errors' => [
    //                             ['code' => 'coupon', 'message' => translate('coupon limit is over')]
    //                         ]
    //                     ], 403);
    //                 }
    //             }

    //             //first order coupon type
    //             if($coupon['coupon_type'] == 'first_order') {
    //                 $first_order = $this->order->where(['user_id' => $request->user()->id])->count();
    //                 $total = $this->order->where(['user_id' => $request->user()->id, 'coupon_code' => $request['code'] ])->count();
    //                 if ($total == 0 && $first_order == 0) {
    //                     return response()->json($coupon, 200);
    //                 }else{
    //                     return response()->json([
    //                         'errors' => [
    //                             ['code' => 'coupon', 'message' => translate('This coupon in not valid for you!')]
    //                         ]
    //                     ], 403);
    //                 }
    //             }

    //             //free delivery
    //             if($coupon['coupon_type'] == 'free_delivery') {
    //                 $total = $this->order->where(['user_id' => $request->user()->id, 'coupon_code' => $request['code'] ])->count();
    //                 if ($total < $coupon['limit']) {
    //                     return response()->json($coupon, 200);
    //                 }else{
    //                     return response()->json([
    //                         'errors' => [
    //                             ['code' => 'coupon', 'message' => translate('This coupon in not valid for you!')]
    //                         ]
    //                     ], 403);
    //                 }
    //             }

    //             //customer wise
    //             if($coupon['coupon_type'] == 'customer_wise') {

    //                 $total = $this->order->where(['user_id' => $request->user()->id, 'coupon_code' => $request['code'] ])->count();

    //                 if ($coupon['customer_id'] != $request->user()->id){
    //                     return response()->json([
    //                         'errors' => [
    //                             ['code' => 'coupon', 'message' => translate('This coupon in not valid for you!')]
    //                         ]
    //                     ], 403);
    //                 }

    //                 if ($total < $coupon['limit']) {
    //                     return response()->json($coupon, 200);
    //                 }else{
    //                     return response()->json([
    //                         'errors' => [
    //                             ['code' => 'coupon', 'message' => translate('This coupon in not valid for you!')]
    //                         ]
    //                     ], 403);
    //                 }
    //             }

    //         } else {
    //             return response()->json([
    //                 'errors' => [
    //                     ['code' => 'coupon', 'message' => 'not found!']
    //                 ]
    //             ], 404);
    //         }
    //     } catch (\Exception $e) {
    //         return response()->json(['errors' => $e], 403);
    //     }
    // }

    public function get_delivery_charge($cartProducts, $region_id)
    {   
        $totalFrozenWeight = ($cartProducts->sum('frozen_weight')/1000) ?? 0;
        $totalDryProductAmount = $cartProducts->sum('dry_product_amount');
        
        $regionDetails1= in_array($region_id,['6', '8', '9']);
        $regionDetails2= in_array($region_id,['1', '2', '3','4','5','7']);

        $regionDetails = Regions::find($region_id);
        
        $deliveryCharge = 0;

        if ($regionDetails==$regionDetails2){

            if($totalFrozenWeight >= 5 || $totalFrozenWeight == 0){
                $deliveryCharge += 0;
            }                
            else{
                $deliveryCharge += 1500;
            }
            if($totalDryProductAmount >= 6500 || $totalDryProductAmount == 0){
                $deliveryCharge += 0;
            }
            else{
                $deliveryCharge += 600;
            }
            
        } elseif ($regionDetails == $regionDetails1) {
            if ($totalDryProductAmount && $totalFrozenWeight) {
                $deliveryCharge = 2000 + 2500; // Dry + Frozen
            } elseif ($totalDryProductAmount) {
                $deliveryCharge = 2000; // Only Dry
            } elseif ($totalFrozenWeight) {
                $deliveryCharge = 2500; // Only Frozen
            }
        }

        return $deliveryCharge;
    }
}

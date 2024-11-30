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
    private $coupon;
    private $order;

    public function __construct(Coupon $coupon, Order $order)
    {
        $this->coupon = $coupon;
        $this->order = $order;
    }

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

            $validCoupons = [];
            $cartDetails = Cart::with('product')->where(['user_id'=>$request->user()->id])->get();
            $orderAmount = $cartDetails->sum('sub_total') + $cartDetails->sum('eight_percent') + $cartDetails->sum('ten_percent');

            foreach($coupons as $coupon) {
                if(!empty($coupon->min_purchase)) {
                    if($coupon->min_purchase <= $orderAmount && ($coupon->limit == null || $coupon->limit != 0)) {
                        $validCoupons[] = $coupon;
                    }
                }
            }

            return response()->json($validCoupons, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

   public function apply(Request $request)
   {
       try {
           $validator = Validator::make($request->all(), [
               'code' => 'required'
           ]);

           if ($validator->fails()) {
               return response()->json(['errors' => $validator->errors()], 422);
           }

           $coupon = $this->coupon->active()->where(['code' => $request['code']])->first();
           $cartDetails = Cart::with('product')->where(['user_id'=>$request->user()->id])->get();

           if(empty($cartDetails)) {
               return response()->json([
                   'errors' => [
                       ['code' => 'cart', 'message' => 'Cart is empty']
                   ]
               ], 404);
           }

           // Get region_id from cart's address or request
           $region_id = !empty($request->current_region_id) ? $request->current_region_id : null;
           if (!$region_id) {
               return response()->json([
                   'errors' => [
                       ['code' => 'region_id', 'message' => 'Current region id is required for delivery calculation']
                   ]
               ], 422);
           }

           // Calculate all amounts
           $subTotal = $cartDetails->sum('sub_total');
           $tenPercentTax = $cartDetails->sum('ten_percent');
           $deliveryCharge = $this->get_delivery_charge($cartDetails, $region_id); // Get correct delivery charge

           // Calculate coupon discount
           $discountPrice = 0;
           if ($coupon) {
               if ($coupon['discount_type'] == "percent") {
                   $discountPrice = ($subTotal * $coupon['discount'])/100;
                   if($discountPrice > $coupon['max_discount']) {
                       $discountPrice = $coupon['max_discount'];
                   }
               } else {
                   $discountPrice = $coupon['discount'];
               }
           }

           // Calculate total
           $total = $subTotal + $deliveryCharge + $tenPercentTax - $discountPrice;

           // Apply wallet if used
           $user = auth()->user();
           $redeem_points = ($request->use_wallet === 'true') ? $user->wallet_balance : 0;
           $walletDeduction = 0;
           if ($request->use_wallet === 'true' && $redeem_points > 0) {
               $walletDeduction = min($redeem_points, $total);
               $total -= $walletDeduction;
           }

           $response = [
               'sub_total' => $subTotal,
               'delivery_charge' => $deliveryCharge,
               'ten_percent_tax' => $tenPercentTax,
               'discount_type' => $coupon['discount_type'] ?? null,
               'discount' => $coupon['discount'] ?? 0,
               'discount_price' => $discountPrice,
               'wallet_deduction' => $walletDeduction,
               'orderAmount' => round($total, 2),
               'total_breakdown' => [
                   'subtotal' => $subTotal,
                   'delivery' => $deliveryCharge,
                   'tax' => $tenPercentTax,
                   'coupon' => -$discountPrice,
                   'wallet' => -$walletDeduction,
                   'final' => $total
               ]
           ];

           return response()->json($response, 200);

       } catch (\Exception $e) {
           \Log::error('Coupon error: ' . $e->getMessage());
           return response()->json([
               'errors' => [['code' => 'error', 'message' => $e->getMessage()]]
           ], 500);
       }
   }

    public function get_delivery_charge($cartProducts, $region_id)
    {
        $totalFrozenWeight = ($cartProducts->sum('frozen_weight')/1000) ?? 0;
        $totalDryProductAmount = $cartProducts->sum('dry_product_amount');

        $deliveryCharge = 0;

        $specialRegions = ['6', '8', '9'];
        $standardRegions = ['1', '2', '3', '4', '5', '7'];

        if (in_array((string)$region_id, $specialRegions)) {
            $hasDryProducts = $totalDryProductAmount > 0;
            $hasFrozenProducts = $totalFrozenWeight > 0;

            if ($hasDryProducts && $hasFrozenProducts) {
                $deliveryCharge = 4500;
            } elseif ($hasDryProducts) {
                $deliveryCharge = 2000;
            } elseif ($hasFrozenProducts) {
                $deliveryCharge = 2500;
            }
        } elseif (in_array((string)$region_id, $standardRegions)) {
            if ($totalFrozenWeight > 0 && $totalFrozenWeight < 5) {
                $deliveryCharge += 1500;
            }

            if ($totalDryProductAmount > 0 && $totalDryProductAmount < 6500) {
                $deliveryCharge += 600;
            }
        }

        return $deliveryCharge;
    }
}

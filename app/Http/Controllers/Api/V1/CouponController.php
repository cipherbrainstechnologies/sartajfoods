<?php

namespace App\Http\Controllers\Api\V1;

use App\CentralLogics\Helpers;
use App\Http\Controllers\Controller;
use App\Model\Coupon;
use App\Model\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
            $coupon = $this->coupon->where('status', 1)
                ->where('start_date', '<=', now()->format('Y-m-d'))
                ->where('expire_date', '>=', now()->format('Y-m-d'))
                ->where(function($query) use ($request) {
                    $query->where('customer_id', $request->user()->id)
                        ->orWhere('customer_id', null);
                })
                ->get();
            return response()->json($coupon, 200);
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function apply(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
        ]);

        if ($validator->errors()->count()>0) {
            return response()->json(['errors' => Helpers::error_processor($validator)], 403);
        }

        try {
            $coupon = $this->coupon->active()->where(['code' => $request['code']])->first();
            if (isset($coupon)) {

                //default coupon type
                if ($coupon['coupon_type'] == 'default') {
                    $total = $this->order->where(['user_id' => $request->user()->id, 'coupon_code' => $request['code']])->count();
                    if ($total < $coupon['limit']) {
                        return response()->json($coupon, 200);
                    }else{
                        return response()->json([
                            'errors' => [
                                ['code' => 'coupon', 'message' => translate('coupon limit is over')]
                            ]
                        ], 403);
                    }
                }

                //first order coupon type
                if($coupon['coupon_type'] == 'first_order') {
                    $first_order = $this->order->where(['user_id' => $request->user()->id])->count();
                    $total = $this->order->where(['user_id' => $request->user()->id, 'coupon_code' => $request['code'] ])->count();
                    if ($total == 0 && $first_order == 0) {
                        return response()->json($coupon, 200);
                    }else{
                        return response()->json([
                            'errors' => [
                                ['code' => 'coupon', 'message' => translate('This coupon in not valid for you!')]
                            ]
                        ], 403);
                    }
                }

                //free delivery
                if($coupon['coupon_type'] == 'free_delivery') {
                    $total = $this->order->where(['user_id' => $request->user()->id, 'coupon_code' => $request['code'] ])->count();
                    if ($total < $coupon['limit']) {
                        return response()->json($coupon, 200);
                    }else{
                        return response()->json([
                            'errors' => [
                                ['code' => 'coupon', 'message' => translate('This coupon in not valid for you!')]
                            ]
                        ], 403);
                    }
                }

                //customer wise
                if($coupon['coupon_type'] == 'customer_wise') {

                    $total = $this->order->where(['user_id' => $request->user()->id, 'coupon_code' => $request['code'] ])->count();

                    if ($coupon['customer_id'] != $request->user()->id){
                        return response()->json([
                            'errors' => [
                                ['code' => 'coupon', 'message' => translate('This coupon in not valid for you!')]
                            ]
                        ], 403);
                    }

                    if ($total < $coupon['limit']) {
                        return response()->json($coupon, 200);
                    }else{
                        return response()->json([
                            'errors' => [
                                ['code' => 'coupon', 'message' => translate('This coupon in not valid for you!')]
                            ]
                        ], 403);
                    }
                }

            } else {
                return response()->json([
                    'errors' => [
                        ['code' => 'coupon', 'message' => 'not found!']
                    ]
                ], 403);
            }
        } catch (\Exception $e) {
            return response()->json(['errors' => $e], 403);
        }
    }
}

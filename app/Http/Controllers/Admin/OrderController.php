<?php

namespace App\Http\Controllers\Admin;

use App\CentralLogics\CustomerLogic;
use App\CentralLogics\Helpers;
use App\CentralLogics\OrderLogic;
use App\Http\Controllers\Controller;
use App\Model\Branch;
use App\Model\BusinessSetting;
use App\Model\DeliveryMan;
use App\Model\Order;
use App\Model\OrderDetail;
use App\Model\Product;
use App\User;
use App\Model\TimeSlot;
use Box\Spout\Common\Exception\InvalidArgumentException;
use Box\Spout\Common\Exception\IOException;
use Box\Spout\Common\Exception\UnsupportedTypeException;
use Box\Spout\Writer\Exception\WriterNotOpenedException;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Rap2hpoutre\FastExcel\FastExcel;
use Symfony\Component\HttpFoundation\StreamedResponse;
use function App\CentralLogics\translate;
use Illuminate\Support\Facades\Response;
use PDF;
use App\Model\OrderHistory; 
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function __construct(
        private Branch $branch,
        private BusinessSetting $business_setting,
        private DeliveryMan $delivery_man,
        private Order $order,
        private OrderDetail $order_detail,
        private Product $product,
        private User $user
    ){}

    public function processOrder($order_id){
        Mail::to('mukesh@silverwebbuzz.com')->send(new \App\Mail\OrderPlaced($order_id));
    }

    /**
     * @param Request $request
     * @param $status
     * @return Factory|View|Application
     */
    public function list(Request $request, $status): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $query_param = [];
        $search = $request['search'];

        $branches = $this->branch->all();
        $branch_id = $request['branch_id'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];

        $this->order->where(['checked' => 0])->update(['checked' => 1]);
        // dd($status);

        if ($status != 'all') {
            $query = $this->order->with(['customer', 'branch'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })->where(['order_status' => $status]);
        } else {
            $query = $this->order->with(['customer', 'branch'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                });
                // dd(2);
        }

        $query_param = ['branch_id' => $branch_id, 'start_date' => $start_date,'end_date' => $end_date ];

        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('order_status', 'like', "%{$value}%")
                        ->orWhere('payment_status', 'like', "{$value}%")
                        ->orWhereHas('customer', function ($q) use ($value) {
                            $q->where('f_name', 'like', "%{$value}%")
                            ->orWhere('l_name', 'like', "%{$value}%")
                            ->orWhere('phone', 'like', "%{$value}%");
                        });
                }
            });
            $query_param = ['search' => $request['search'], 'branch_id' => $request['branch_id'], 'start_date' => $request['start_date'],'end_date' => $request['end_date'] ];
        }

        $orders = $query->notPos()->orderBy('id', 'desc')->paginate(Helpers::getPagination())->appends($query_param);
        foreach ($orders as $order) {
            $orderDetails = collect($order->details);
            $totalOrderAmount = 0;
            $EightPercentTax = 0;
            $TenPercentTax = 0;
    
            foreach ($orderDetails as $orderDetail) {
                $subtotal = ($orderDetail->price * $orderDetail->quantity) - ($orderDetail->discount_on_product * $orderDetail->quantity);
                $totalOrderAmount += $subtotal;
                $EightPercentTax += $orderDetail->eight_percent_tax;
                $TenPercentTax += $orderDetail->ten_percent_tax;
            }
    
            $EightPercentTax = round($EightPercentTax);
            $TenPercentTax = round($TenPercentTax);
    
            if ($order->coupon_discount_amount) {
                $totalOrderAmount -= $order->coupon_discount_amount;
            }
    
            if ($order->extra_discount) {
                $totalOrderAmount -= $order->extra_discount;
            }
    
            if ($order->order_type == 'delivery') {
                $deliveryCharge = $order->delivery_charge ?? $order->free_delivery_amount;
                $totalOrderAmount += $deliveryCharge;
            }
    
            $totalOrderAmount += $EightPercentTax + $TenPercentTax;
            $order->calculated_order_amount = $totalOrderAmount;
        }

        $count_data = [
            'pending' => $this->order->notPos()->where(['order_status'=>'pending'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })->count(),

            'confirmed' => $this->order->notPos()->where(['order_status'=>'confirmed'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })->count(),

            'processing' => $this->order->notPos()->where(['order_status'=>'processing'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })->count(),

            'out_for_delivery' => $this->order->notPos()->where(['order_status'=>'out_for_delivery'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })->count(),

            'delivered' => $this->order->notPos()->where(['order_status'=>'delivered'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })->count(),

            'canceled' => $this->order->notPos()->where(['order_status'=>'canceled'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })->count(),

            'returned' => $this->order->notPos()->where(['order_status'=>'returned'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })->count(),

            'failed' => $this->order->notPos()->where(['order_status'=>'failed'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })->count(),
        ];

        return view('admin-views.order.list', compact('orders', 'status', 'search', 'branches', 'branch_id', 'start_date', 'end_date', 'count_data'));
    }

    /**
     * @param $id
     * @return View|Factory|RedirectResponse|Application
     */
    public function details($id): Factory|View|Application|RedirectResponse
    {   
        $order = $this->order->with('details', 'history','browser_history')->where(['id' => $id])->first();
        if(!empty($order)){
            $orderDetails =collect($order->details);
            $EightPercentTax = $orderDetails->sum('eight_percent_tax');
            $TenPercentTax = $orderDetails->sum('ten_percent_tax');

            $delivery_man = $this->delivery_man->where(['is_active'=>1])
            ->where(function($query) use ($order) {
                $query->where('branch_id', $order->branch_id)
                    ->orWhere('branch_id', 0);
            })
            ->get();
        }
        // $orderDetails =collect($order->details);
        // $EightPercentTax = $orderDetails->sum('eight_percent_tax');
        // $TenPercentTax = $orderDetails->sum('ten_percent_tax');
        
        if (isset($order)) {
            $products =  $this->product->all();
            return view('admin-views.order.order-view', compact('order', 'delivery_man','EightPercentTax','TenPercentTax', 'products'));
        } else {
            Toastr::info(translate('No more orders!'));
            return back();
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): \Illuminate\Http\JsonResponse
    {

        $key = explode(' ', $request['search']);
        $orders = $this->order->where(function ($q) use ($key) {
            foreach ($key as $value) {
                $q->orWhere('id', 'like', "%{$value}%")
                    ->orWhere('order_status', 'like', "%{$value}%")
                    ->orWhere('transaction_reference', 'like', "%{$value}%");
            }
        })->latest()->paginate(2);

        return response()->json([
            'view' => view('admin-views.order.partials._table', compact('orders'))->render(),
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function date_search(Request $request): \Illuminate\Http\JsonResponse
    {
        $dateData = ($request['dateData']);

        $orders = $this->order->where(['delivery_date' => $dateData])->latest()->paginate(10);
        // $timeSlots = $orders->pluck('time_slot_id')->unique()->toArray();
        // if ($timeSlots) {

        //     $timeSlots = TimeSlot::whereIn('id', $timeSlots)->get();
        // } else {
        //     $timeSlots = TimeSlot::orderBy('id')->get();

        // }
        // dd($orders);

        return response()->json([
            'view' => view('admin-views.order.partials._table', compact('orders'))->render(),
            // 'timeSlot' => $timeSlots
        ]);

    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function time_search(Request $request): \Illuminate\Http\JsonResponse
    {

        $orders = $this->order->where(['time_slot_id' => $request['timeData']])->where(['delivery_date' => $request['dateData']])->get();
        // dd($orders)->toArray();

        return response()->json([
            'view' => view('admin-views.order.partials._table', compact('orders'))->render(),
        ]);

    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function status(Request $request): \Illuminate\Http\RedirectResponse
    {
        $order = $this->order->find($request->id);

        if (in_array($order->order_status, ['delivered', 'failed'])) {
            Toastr::warning(translate('you_can_not_change_the_status_of_a_completed_order'));
            return back();
        }

        if ($request->order_status == 'delivered' && $order['transaction_reference'] == null && !in_array($order['payment_method'],['cash_on_delivery','wallet'])) {
            Toastr::warning(translate('add_your_payment_reference_first'));
            return back();
        }

        if ( $request->order_status == 'out_for_delivery' && $order['delivery_man_id'] == null && $order['order_type'] != 'self_pickup') {
            Toastr::warning(translate('Please assign delivery man first!'));
            return back();
        }

        if ($request->order_status == 'returned' || $request->order_status == 'failed' || $request->order_status == 'canceled') {
            foreach ($order->details as $detail) {

                if ($detail['is_stock_decreased'] == 1) {
                    $product = $this->product->find($detail['product_id']);

                    if($product != null){
                        $type = json_decode($detail['variation'])[0]->type;
                        $var_store = [];
                        foreach (json_decode($product['variations'], true) as $var) {
                            if ($type == $var['type']) {
                                $var['stock'] += $detail['quantity'];
                            }
                            $var_store[] = $var;
                        }
                        $this->product->where(['id' => $product['id']])->update([
                            'variations' => json_encode($var_store),
                            'total_stock' => $product['total_stock'] + $detail['quantity'],
                        ]);
                        $this->order_detail->where(['id' => $detail['id']])->update([
                            'is_stock_decreased' => 0,
                        ]);
                    }
                }else{
                    Toastr::warning(translate('Product_deleted'));
                }

            }
        } else {
            foreach ($order->details as $detail) {
                if ($detail['is_stock_decreased'] == 0) {
                    $product = $this->product->find($detail['product_id']);
                    if($product != null){
                        //check stock
                        foreach ($order->details as $c) {
                            $product = $this->product->find($c['product_id']);
                            $type = json_decode($c['variation'])[0]->type;
                            foreach (json_decode($product['variations'], true) as $var) {
                                if ($type == $var['type'] && $var['stock'] < $c['quantity']) {
                                    Toastr::error(translate('Stock is insufficient!'));
                                    return back();
                                }
                            }
                        }

                        $type = json_decode($detail['variation'])[0]->type;
                        $var_store = [];
                        foreach (json_decode($product['variations'], true) as $var) {
                            if ($type == $var['type']) {
                                $var['stock'] -= $detail['quantity'];
                            }
                            $var_store[] = $var;
                        }
                        $this->product->where(['id' => $product['id']])->update([
                            'variations' => json_encode($var_store),
                            'total_stock' => $product['total_stock'] - $detail['quantity'],
                        ]);
                        $this->order_detail->where(['id' => $detail['id']])->update([
                            'is_stock_decreased' => 1,
                        ]);
                    }
                    else{
                        Toastr::warning(translate('Product_deleted'));
                    }

                }
            }
        }

        if ($request->order_status == 'delivered') {
            if($order->user_id) {
                CustomerLogic::create_loyalty_point_transaction($order->user_id, $order->id, $order->order_amount, 'order_place');
            }

            $user = $this->user->find($order->user_id);
            $is_first_order = $this->order->where('user_id', $user->id)->count('id');
            $referred_by_user = $this->user->find($user->referred_by);

            if ($is_first_order < 2 && isset($user->referred_by) && isset($referred_by_user)){
                if($this->business_setting->where('key','ref_earning_status')->first()->value == 1) {
                    CustomerLogic::referral_earning_wallet_transaction($order->user_id, 'referral_order_place', $referred_by_user->id);
                }
            }
        }

        $order->order_status = $request->order_status;
        $order->save();

        $fcm_token = isset($order->customer) ? $order->customer->cm_firebase_token : null;
        $value = Helpers::order_status_update_message($request->order_status);
        try {
            if ($value) {
                $data = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                    'type' => 'order'
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
            }
        } catch (\Exception $e) {
            Toastr::warning(\App\CentralLogics\translate('Push notification failed for Customer!'));
        }

        //delivery man notification
        if ($request->order_status == 'processing' && $order->delivery_man != null) {
            $fcm_token = $order->delivery_man->fcm_token;
            $value = \App\CentralLogics\translate('One of your order is in processing');
            try {
                if ($value) {
                    $data = [
                        'title' => translate('Order'),
                        'description' => $value,
                        'order_id' => $order['id'],
                        'image' => '',
                        'type' => 'order'
                    ];
                    Helpers::send_push_notif_to_device($fcm_token, $data);
                }
            } catch (\Exception $e) {
                Toastr::warning(\App\CentralLogics\translate('Push notification failed for DeliveryMan!'));
            }
        }

        Toastr::success(translate('Order status updated!'));
        return back();
    }

    /**
     * @param $order_id
     * @param $delivery_man_id
     * @return JsonResponse
     */
    public function add_delivery_man($order_id, $delivery_man_id): \Illuminate\Http\JsonResponse
    {
        if ($delivery_man_id == 0) {
            return response()->json([], 401);
        }

        $order = $this->order->find($order_id);

        if ($order->order_status == 'pending' || $order->order_status == 'confirmed' || $order->order_status == 'delivered' || $order->order_status == 'returned' || $order->order_status == 'failed' || $order->order_status == 'canceled') {
            return response()->json(['status' => false], 200);
        }

        $order->delivery_man_id = $delivery_man_id;
        $order->save();

        $fcm_token = $order->delivery_man->fcm_token;
        $customer_fcm_token = $order->customer->cm_firebase_token;
        $value = Helpers::order_status_update_message('del_assign');
        try {
            if ($value) {
                $data = [
                    'title' => translate('Order'),
                    'description' => $value,
                    'order_id' => $order['id'],
                    'image' => '',
                    'type' => 'order'
                ];
                Helpers::send_push_notif_to_device($fcm_token, $data);
                $cs_notify_message = Helpers::order_status_update_message('customer_notify_message');
                if($cs_notify_message) {
                    $data['description'] = $cs_notify_message;
                    Helpers::send_push_notif_to_device($customer_fcm_token, $data);
                }
            }
        } catch (\Exception $e) {
            Toastr::warning(\App\CentralLogics\translate('Push notification failed for DeliveryMan!'));
        }

        Toastr::success('Deliveryman successfully assigned/changed!');
        return response()->json(['status' => true], 200);
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function payment_status(Request $request): \Illuminate\Http\RedirectResponse
    {
        $order = $this->order->find($request->id);
        if ($request->payment_status == 'paid' && $order['transaction_reference'] == null && $order['payment_method'] != 'cash_on_delivery') {
            Toastr::warning(translate('Add your payment reference code first!'));
            return back();
        }
        $order->payment_status = $request->payment_status;
        $order->save();
        Toastr::success(translate('Payment status updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function update_shipping(Request $request, $id): \Illuminate\Http\RedirectResponse
    {
        $request->validate([
            'contact_person_name' => 'required',
            'address_type' => 'required',
            'contact_person_number' => 'required',
            'address' => 'required',
        ]);

        $address = [
            'contact_person_name' => $request->contact_person_name,
            'contact_person_number' => $request->contact_person_number,
            'address_type' => $request->address_type,
            'address' => $request->address,
            'longitude' => $request->longitude,
            'latitude' => $request->latitude,
            'road' => $request->road,
            'house' => $request->house,
            'floor' => $request->floor,
            'created_at' => now(),
            'updated_at' => now(),
        ];

        DB::table('customer_addresses')->where('id', $id)->update($address);
        Toastr::success(translate('Delivery Information updated!'));
        return back();
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function update_time_slot(Request $request)
    {
        if ($request->ajax()) {
            $order = $this->order->find($request->id);
            $order->time_slot_id = $request->timeSlot;
            $order->save();
            $data = $request->timeSlot;

            return response()->json($data);
        }
    }

    /**
     * @param Request $request
     * @return JsonResponse|void
     */
    public function update_deliveryDate(Request $request)
    {
        if ($request->ajax()) {
            $order = $this->order->find($request->id);
            $order->delivery_date = $request->deliveryDate;
           // dd($order);
            $order->save();
            $data = $request->deliveryDate;
            return response()->json($data);
        }
    }

    /**
     * @param $id
     * @return Factory|View|Application
     */
    public function generate_invoice(Request $request,$id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $delivery_charge = 0;
        $delivery_fee =0;
        $timeSlotDetail = translate('All Day');
        $order = $this->order->with('delivery_address','details','customer')->where('id', $id)->first();
        if(!empty($order->time_slot_id)){
            $timeSlot = TimeSlot::where('id',$order->time_slot_id)->first();
            $timeSlotDetail = Helpers::TimeSlot($timeSlot);
        }
        $orderDetails =collect($order->details);
        $EightPercentTax = $orderDetails->sum('eight_percent_tax');
        $TenPercentTax = $orderDetails->sum('ten_percent_tax'); 
        $totalDiscount =   $orderDetails->sum('total_discount');
        $totalTaxPercent = Helpers::calculateTotalTaxAmount($order);
        $totalWeight = ($orderDetails->sum('weight')/1000) ?? 0 ;
        $subTotal = (Helpers::calculateInvoice($id) - $EightPercentTax - $TenPercentTax);
        $delivery_charge = $order->delivery_charge;
        $delivery_fee = $order->free_delivery_amount;

        $totalAmt = ( $subTotal - $order->coupon_discount_amount) + $order->delivery_charge +$delivery_fee;
        
        // Rounded Value Display.
        // 
        $roundedFraction = round($totalAmt - floor($totalAmt), 2);
        if ($roundedFraction > 0.50) {
            // If yes, add 1
            $totalAmt = ceil($totalAmt);
        } elseif ($roundedFraction < 0.50) {
            // If no, subtract 1
            $totalAmt = floor($totalAmt);
        }
        $footer_text = $this->business_setting->where(['key' => 'footer_text'])->first();
        $config['shop_logo'] = Helpers::get_business_settings('logo');
        $config['shop_name'] = Helpers::get_business_settings('restaurant_name');
        $config['phone'] = Helpers::get_business_settings('phone');
        $config['address'] = Helpers::get_business_settings('address');
        $order->shop_detail = $config;
        if($request->language=="ja"){
            $timeSlotDetail = ($timeSlotDetail==="All Day") ? '一日中' : $timeSlotDetail;
            return view('admin-views.order.new_japanese_invoice', compact('timeSlotDetail','order','totalWeight','totalTaxPercent','totalDiscount' ,'footer_text','totalAmt','subTotal','TenPercentTax','EightPercentTax'));
        }else{
            return view('admin-views.order.new_english_invoice', compact('timeSlotDetail','order','totalWeight','totalTaxPercent','totalDiscount' ,'footer_text','totalAmt','subTotal','TenPercentTax','EightPercentTax'));
        }
        // return view('admin-views.order.invoice', compact('order', 'footer_text'));
        // return view('admin-views.order.latest_invoice', compact('order', 'footer_text','totalAmt','TenPercentTax','EightPercentTax'));
    }

    public function downloadInvoicePDF($id): \Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|\Illuminate\Contracts\Foundation\Application
    {
        $order = $this->order->with('delivery_address','details')->where('id', $id)->first();
        $orderDetails =collect($order->details);
        $EightPercentTax = $orderDetails->sum('eight_percent_tax');
        $TenPercentTax = $orderDetails->sum('ten_percent_tax'); 
        $totalDiscount =   $orderDetails->sum('total_discount');
        $totalTaxPercent = Helpers::calculateTotalTaxAmount($order);
        $subTotal = (Helpers::calculateInvoice($id));
        $totalAmt = ($subTotal - $order->coupon_discount_amount) + $order->delivery_charge ;
        $footer_text = $this->business_setting->where(['key' => 'footer_text'])->first();
        $config['shop_name'] = Helpers::get_business_settings('restaurant_name');
        $config['phone'] = Helpers::get_business_settings('phone');
        $config['address'] = Helpers::get_business_settings('address');
        $order->shop_detail = $config;
        // return view('admin-views.order.new_invoice', compact('order','totalTaxPercent','totalDiscount' ,'footer_text','totalAmt','subTotal','TenPercentTax','EightPercentTax'));

        // Generate PDF
        $pdf = PDF::loadView('admin-views.order.new_invoice', compact('order','totalTaxPercent','totalDiscount' ,'footer_text','totalAmt','subTotal','TenPercentTax','EightPercentTax'));

        // Save the PDF temporarily
        $tempPath = storage_path('app/public/invoices');
        $filename = 'invoice_' . $order->id . '.pdf';
        $pdf->save($tempPath . '/' . $filename);

        // Provide a link to download
        $downloadLink = url("/download-temp-pdf/{$filename}");

        return  $downloadLink;
    }

    /**
     * @param Request $request
     * @param $id
     * @return RedirectResponse
     */
    public function add_payment_ref_code(Request $request, $id)
    {
        $this->order->where(['id' => $id])->update([
            'transaction_reference' => $request['transaction_reference'],
        ]);

        Toastr::success(translate('Payment reference code is added!'));
        return back();
    }

    /**
     * @param $id
     * @return RedirectResponse
     */
    public function branch_filter($id): \Illuminate\Http\RedirectResponse
    {
        session()->put('branch_filter', $id);
        return back();
    }

    /**
     * @param Request $request
     * @param $status
     * @return string|StreamedResponse
     * @throws IOException
     * @throws InvalidArgumentException
     * @throws UnsupportedTypeException
     * @throws WriterNotOpenedException
     */
    public function export_orders(Request $request, $status): StreamedResponse|string
    {
        $query_param = [];
        $search = $request['search'];
        $branch_id = $request['branch_id'];
        $start_date = $request['start_date'];
        $end_date = $request['end_date'];
        if ($status != 'all') {
            $query = $this->order->with(['customer', 'branch'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                })->where(['order_status' => $status]);
        } else {
            $query = $this->order->with(['customer', 'branch'])
                ->when((!is_null($branch_id) && $branch_id != 'all'), function ($query) use ($branch_id) {
                    return $query->where('branch_id', $branch_id);
                })->when((!is_null($start_date) && !is_null($end_date)), function ($query) use ($start_date, $end_date) {
                    return $query->whereDate('created_at', '>=', $start_date)
                        ->whereDate('created_at', '<=', $end_date);
                });
        }
       
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('order_status', 'like', "%{$value}%")
                        ->orWhere('payment_status', 'like', "%{$value}%");
                        
                }
            });
            $query_param = ['search' => $request['search']];
        }

        //$orders = $query->notPos()->orderBy('id', 'desc')->paginate(Helpers::getPagination())->appends($query_param);
        $orders = $query->notPos()->orderBy('id', 'desc')->get();

        $storage = [];

        foreach($orders as $order){
            $branch = $order->branch ? $order->branch->name : '';
            $customer = $order->customer ? $order->customer->f_name .' '. $order->customer->l_name : 'Customer Deleted';
            //$delivery_address = $order->delivery_address ? $order->delivery_address['address'] : '';
            $delivery_man = $order->delivery_man ? $order->delivery_man->f_name .' '. $order->delivery_man->l_name : '';
            $timeslot = $order->time_slot ? $order->time_slot->start_time .' - '. $order->time_slot->end_time : '';

            $storage[] = [
                'order_id' => $order['id'],
                'customer' => $customer,
                'order_amount' => $order['order_amount'],
                'coupon_discount_amount' => $order['coupon_discount_amount'],
                'payment_status' => $order['payment_status'],
                'order_status' => $order['order_status'],
                'total_tax_amount'=>$order['total_tax_amount'],
                'payment_method' => $order['payment_method'],
                'transaction_reference' => $order['transaction_reference'],
               // 'delivery_address' => $delivery_address,
                'delivery_man' => $delivery_man,
                'delivery_charge' => $order['delivery_charge'],
                'coupon_code' => $order['coupon_code'],
                'order_type' => $order['order_type'],
                'branch'=>  $branch,
                'time_slot_id' => $timeslot,
                'date' => $order['date'],
                'delivery_date' => $order['delivery_date'],
                'extra_discount' => $order['extra_discount'],
            ];
        }
        //return $storage;
        return (new FastExcel($storage))->download('orders.xlsx');
    }

    public function shpping_list($order_id)
    {
        $order = $this->order->where('id', $order_id)->first();
        $order->delivery_address = (array)$order->delivery_address;
        return view('admin-views.order.shipping', compact('order'));
    }

     /**
     * @param Request $request
     */
    public function order_history(Request $request)
    {
        $status = 0;
        $orderHistoryData = OrderHistory::with('order','order.customer')->where('order_id', $request->id)->first();
        $data = [
            'order_id' => $request->id,
            'status' => $request->order_status,
            'comment' => !empty($request->comment) ? $request->comment : null,
            'is_customer_notify' => ($request->notify_customer === "true") ? 1 :0, 
        ];        
        $history = OrderHistory::create($data)->order();

        $order = Order::find($request->id);
        $order->order_status = $request->order_status;
        if($request->order_status == 'delivered'){
            $order->payment_status = 'paid';
        }
        if($request->order_status == 'delivered'){
            $order->payment_status = 'unpaid';
        }
        $order->save();
        // $status = !empty($history) ? 1 : 0;
       
        // if($request->notify_customer === "true"){
            Mail::to($orderHistoryData->order->customer->email)->send(new \App\Mail\OrderPlaced($request->id));
            \Log::info('Place Order Mail sent to user successfully.');
        // }
        
        
        return response()->json($status);
    }

    public function order_tracking(Request $request){
        $trackingOrderStatus = Order::where('id',$request->id)->update(['tracking_id'=>$request->trackingId]);
        if($trackingOrderStatus){
            return response()->json($trackingOrderStatus);
        }
        return response()->json($trackingOrderStatus);
    }

     /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function delete(Request $request): \Illuminate\Http\RedirectResponse
    {
        $order = $this->order->find($request->id);
        $order_details = $this->order_detail->where('order_id', $request->id)->get();
        foreach($order_details as $order_detail) {
            if(!empty($order_detail)) {
                $order_detail_obj = $this->order_detail->find($order_detail->id);
                $product_obj = $this->product->find($order_detail->product_id);
                $product_obj->total_stock = $product_obj->total_stock + $order_detail->quantity;
                $product_obj->save();
                $order_detail_obj->delete();
            }
        }
        $order->delete();
        Toastr::success(translate('Order deleted!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function productAdd(Request $request): \Illuminate\Http\RedirectResponse
    {
        // dd($request);
        $product_detail = $this->product->find($request->product);
        
        $order_detail = $this->order_detail;
        $order_detail->product_id = $product_detail->id;
        $order_detail->order_id = $request->order_id;
        $order_detail->price = $product_detail->price;
        $order_detail->product_details = json_encode($product_detail);
        $order_detail->quantity = $request->quantity;
        $tax = !empty($product_detail->tax) ? $product_detail->price * ($product_detail->tax/100) : 0;
        $order_detail->tax_amount = $tax;
        $order_detail->eight_percent_tax = ($product_detail->tax === 8.00) ? $tax : 0;
        $order_detail->ten_percent_tax = ($product_detail->tax === 10.00) ? $tax : 0;
        $order_detail->created_at = now();
        $order_detail->updated_at = now();
        $order_detail->unit = $product_detail->unit;
        
        $order_detail->save();
        $this->updateOrderAmount($request->order_id);

        $product_detail->total_stock = $product_detail->total_stock - $request->quantity;
        $product_detail->save();
        
        Toastr::success(translate('Product Added!'));
        return back();
    }

    /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function check_quantity(Request $request)
    {
        // dd(1);
        $product_id_by_order = '';
        if(!empty($request->order_id)) {
            $product_id_by_order = $this->order_detail->find($request->order_id);
        }
        $id = !empty($request->product_id) ? $request->product_id : $product_id_by_order->product_id;
        $product_data = $this->product->find($id);
        $quantity = !empty($product_data->total_stock) ? $product_data->total_stock : 0;
        return response()->json($quantity);
    }

     /**
     * @param Request $request
     * @return RedirectResponse
     */
    public function update_quantity(Request $request): \Illuminate\Http\RedirectResponse
    {
        // dd($request);
        $order_detail = $this->order_detail->find($request->order_detail_id);
        $product_data = $this->product->find($request->product);
        $quantity_data = $order_detail->quantity;
        // dd($request->quantity_update, $order_detail->quantity);
        if($request->quantity_update === $order_detail->quantity) {
            $quantity_data = $order_detail->quantity;
        }
        if($request->quantity_update <= $order_detail->quantity) {
            $quantity_data = $product_data->total_stock + abs(($request->quantity_update - $order_detail->quantity));
        }
        if($request->quantity_update >= $order_detail->quantity) {
            $quantity_data = $product_data->total_stock - abs(($request->quantity_update - $order_detail->quantity));
        }
        
        $order_detail->quantity = $request->quantity_update;
        $order_detail->updated_at = now();

        $product_data->total_stock = $quantity_data;

        $product_data->save();

        $order_detail->save();
        $this->updateOrderAmount($order_detail->order_id);

        Toastr::success(translate('Quantity Updated!'));
        return back();
    }
    public function updateOrderAmount($orderId)
    {
    // Get all order details for the given order_id
    $orderDetails = OrderDetail::where('order_id', $orderId)->get();
    //$order = Order::where('id', $orderId)->get();
    $order = Order::findOrFail($orderId);
    // Initialize total order amount
    $deliveryAddress = \App\Model\CustomerAddress::find($order->delivery_address_id);
    $totalOrderAmount = 0;
    $totalEightPercentTax = 0;
    $totalTenPercentTax = 0;

    // Iterate over each order detail
    foreach ($orderDetails as $orderDetail) {
        // Calculate subtotal for each order detail considering quantity, price, and taxes
        $subtotal = ($orderDetail->price * $orderDetail->quantity) 
                    -($orderDetail->discount_on_product * $orderDetail->quantity);
        
        // Add subtotal to the total order amount
        $totalOrderAmount += $subtotal;
        $totalEightPercentTax += $orderDetail->eight_percent_tax;
        $totalTenPercentTax += $orderDetail->ten_percent_tax;
    }
    
    $totalEightPercentTax = round($totalEightPercentTax);
    $totalTenPercentTax = round($totalTenPercentTax);
    $finalTotalAmount = $totalOrderAmount + $totalEightPercentTax + $totalTenPercentTax;
    // Calculate the delivery charge based on region
    $deliveryCharge = $this->calculateDeliveryCharge($deliveryAddress->state_name,$order->id, $totalOrderAmount);
     // Update the delivery charge in the orders table
     Order::where('id', $orderId)->update(['delivery_charge' => $deliveryCharge]);
    // Add delivery charge to the total order amount
    $finalTotalAmount += $deliveryCharge;
    if ($order->coupon_discount_amount) {
        $finalTotalAmount -= $order->coupon_discount_amount;
    }
    if ($order->extra_discount) {
        $finalTotalAmount -= $order->extra_discount;
    }
    
    // Add rounded total tax amounts to the total order amount
    //$totalOrderAmount += $totalEightPercentTax + $totalTenPercentTax;
    // Update the order amount in the orders table
    Order::where('id', $orderId)->update(['order_amount' => $finalTotalAmount]);
  }
  private function calculateDeliveryCharge($stateName, $orderId, $totalOrderAmount)
  {
      $frozenDeliveryCharge = 0;
      $regularDeliveryCharge = 600; // Default delivery charge
  
      $frozenProductDetails = OrderDetail::where('order_id',$orderId)
          ->whereHas('product', function ($query) {
              $query->where('product_type', 1);
          })->get();
  
        if ($frozenProductDetails->isNotEmpty()) {
        $totalFrozenWeight = 0;
        //$totalFrozenQuantity = 0;
        foreach ($frozenProductDetails as $detail) {
        $product = $detail->product;
        $weight = $product->weight;
        $weightClass = $product->weight_class; // 'g' for grams, 'kg' for kilograms

        // Convert weight to kilograms if necessary
        if ($weightClass == 'Gram') {
            $weight /= 1000; // Convert grams to kilograms
        }
        $totalFrozenWeight += $weight * $detail->quantity;
        //$totalFrozenQuantity += $detail->quantity;
        }
        if ($totalFrozenWeight >= 5) {
            if (in_array($stateName, ['Kagoshima', 'Okinawa', 'Hokkaido'])) {
                $frozenDeliveryCharge = 2500 ;
            } else {
                // If the state is Kagoshima, Okinawa, or Hokkaido, use the regular frozen delivery charge
                $frozenDeliveryCharge = 0;
            }
        } else {
            // If the total weight is less than 5kg, use the regular frozen delivery charge
            $frozenDeliveryCharge = $this->getFrozenDeliveryCharge($stateName) ;
        }
        }
        if ($totalOrderAmount > 6500) {
          // Free delivery for regions except Kagoshima, Okinawa, and Hokkaido, if total amount is greater than 6500
          if (in_array($stateName, ['Kagoshima', 'Okinawa', 'Hokkaido'])) {
              $regularDeliveryCharge = 2000;
          } 
        } else {
          // Apply regular delivery charges based on region
          switch ($stateName) {
              case 'Kanto':
              case 'Chubu':
              case 'Hokuriku':
              case 'Shinetsu':
              case 'Tohoku':
              case 'Kansai':
              case 'Chugoku':
              case 'Shikoku':
              case 'Kyushu':
                  $regularDeliveryCharge = 600;
                  break;
              default:
                  $regularDeliveryCharge = 600; // Default delivery charge for other regions
                  break;
          }
      }
  
      // If total order amount is greater than 6500, only add the frozen delivery charge
      return $totalOrderAmount > 6500 ? $frozenDeliveryCharge : $regularDeliveryCharge + $frozenDeliveryCharge;
  
}
private function getFrozenDeliveryCharge($region)
{
    $frozenCharges = [
        'Kanto' => 1500,
        'Chubu' => 1500,
        'Hokuriku' => 1500,
        'Shinetsu' => 1500,
        'Tohoku' => 1500,
        'Kansai' => 1500,
        'Chugoku' => 1500,
        'Shikoku' => 1500,
        'Kyushu' => 1500,
        'Hokkaido' => 2500,
        'Kagoshima' => 2500,
        'Okinawa' => 2500,
    ];

    return $frozenCharges[$region]; // Default frozen delivery charge if region is not listed
}
}
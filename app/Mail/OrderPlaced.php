<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use App\Model\Order;
use Illuminate\Queue\SerializesModels;
use PDF;
use App\CentralLogics\Helpers;
use App\Model\BusinessSetting;
use Illuminate\Support\Facades\Log;
use Mpdf\Mpdf;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;


class OrderPlaced extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $order_id;

    public function __construct($order_id)
    {
        $this->order_id = $order_id;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {  
        try {
            $order_id = $this->order_id;
            $order = Order::with('delivery_address', 'details')->where('id', $order_id)->first();
            $orderDetails = collect($order->details);
            $totalDiscount =   $orderDetails->sum('total_discount');
            $EightPercentTax = $orderDetails->sum('eight_percent_tax');
            $TenPercentTax = $orderDetails->sum('ten_percent_tax');
            
            // $totalWeight = Helpers::calculateTotalWeightOrder($order_id);
            $totalWeight = ($orderDetails->sum('weight')/1000) ?? 0 ;
            $delivery_fee = $order->free_delivery_amount;
            $totalAmt = (Helpers::calculateInvoice($order->id)) + $order->delivery_charge + $delivery_fee;

            $roundedFraction = round($totalAmt - floor($totalAmt), 2);
            if ($roundedFraction > 0.50) {
                // If yes, add 1
                $totalAmt = ceil($totalAmt);
            } elseif ($roundedFraction < 0.50) {
                // If no, subtract 1
                $totalAmt = floor($totalAmt);
            }
            
            $totalTaxPercent = Helpers::calculateTotalTaxAmount($order_id);
            $subTotal = (Helpers::calculateInvoice($order_id));
            $footer_text = BusinessSetting::where(['key' => 'footer_text'])->first();
            $config['shop_logo'] = Helpers::get_business_settings('logo');
            $config['shop_name'] = Helpers::get_business_settings('restaurant_name');
            $config['phone'] = Helpers::get_business_settings('phone');
            $config['address'] = Helpers::get_business_settings('address');
            $order->shop_detail = $config;
            
            return $this->view('email-templates.customer-order-placed', compact('order_id'))
                ->subject('Order Confirmation: Your Order is Successfully Placed!');
               
        } catch (\Exception $e) {
            Log::error("Error building email: {$e->getMessage()}");
        }
    }
}

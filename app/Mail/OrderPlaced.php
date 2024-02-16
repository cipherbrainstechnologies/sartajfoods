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
        try{
            $order_id = $this->order_id;
            $order =Order::with('delivery_address','details')->where('id', $order_id)->first();
            $orderDetails =collect($order->details);
            $EightPercentTax = $orderDetails->sum('eight_percent_tax');
            $TenPercentTax = $orderDetails->sum('ten_percent_tax');
            $totalAmt = (Helpers::calculateInvoice($order->id)) + $order->delivery_charge;
            $footer_text = BusinessSetting::where(['key' => 'footer_text'])->first();
            $pdf = PDF::loadView('admin-views.order.latest_invoice',  compact('order', 'footer_text','totalAmt','TenPercentTax','EightPercentTax'));
            
            return $this->view('email-templates.customer-order-placed', compact('order_id'))
                ->attachData($pdf->output(), 'invoice.pdf', [
                    'mime' => 'application/pdf',
                ]);
            // return $this->view('email-templates.customer-order-placed', ['order_id' => $order_id]);
        }catch(\Exception $e){
            Log::error("Error building email: {$e->getMessage()}");
        }
       
    }
}

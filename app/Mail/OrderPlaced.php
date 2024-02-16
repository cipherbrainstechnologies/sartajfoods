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

            $mpdfConfig = [
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'fontDir' => public_path('fonts/'), // Change the path to the directory containing your font files
                // 'fontdata' => [
                //     'notosans' => [
                //         'R' => 'NotoSans-Regular.ttf',
                //         'B' => 'NotoSans-Bold.ttf',
                //     ],
                //     'notosanscjk' => [
                //         'R' => 'NotoSansCJK-Regular.ttc',
                //         'B' => 'NotoSansCJK-Bold.ttc',
                //     ],
                // ],
            ];

            $pdf = new \Mpdf\Mpdf($mpdfConfig);
            $pdf->WriteHTML(view('admin-views.order.latest_invoice', compact('order', 'footer_text', 'totalAmt', 'TenPercentTax', 'EightPercentTax'))->render());
            return $this->view('email-templates.customer-order-placed', compact('order_id'))
            ->attachData($pdf->Output('invoice.pdf', 'I'), 'invoice.pdf', [
                'mime' => 'application/pdf',
            ]);
            // $pdf = PDF::loadView('admin-views.order.latest_invoice',  compact('order', 'footer_text','totalAmt','TenPercentTax','EightPercentTax'))->setOptions([
            //     'isHtml5ParserEnabled' => true,
            //     'isPhpEnabled' => true,
            //     'isFontSubsettingEnabled' => true,
            // ]);
            
            // return $this->view('email-templates.customer-order-placed', compact('order_id'))
            //     ->attachData($pdf->output(), 'invoice.pdf', [
            //         'mime' => 'application/pdf',
            //     ]);
            // return $this->view('email-templates.customer-order-placed', ['order_id' => $order_id]);
        }catch(\Exception $e){
            Log::error("Error building email: {$e->getMessage()}");
        }
       
    }
}

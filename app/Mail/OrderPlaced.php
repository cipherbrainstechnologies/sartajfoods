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
        // try{
        //     $order_id = $this->order_id;
        //     $order =Order::with('delivery_address','details')->where('id', $order_id)->first();
        //     $orderDetails =collect($order->details);
        //     $EightPercentTax = $orderDetails->sum('eight_percent_tax');
        //     $TenPercentTax = $orderDetails->sum('ten_percent_tax');
        //     $totalAmt = (Helpers::calculateInvoice($order->id)) + $order->delivery_charge;
        //     $footer_text = BusinessSetting::where(['key' => 'footer_text'])->first();

        //     $mpdfConfig = [
        //         'mode' => 'utf-8',
        //         'format' => 'A4',
        //         'orientation' => 'P'
                
        //     ];

        //     $pdf = new \Mpdf\Mpdf($mpdfConfig);
        //     $pdf->WriteHTML(view('admin-views.order.latest_invoice', compact('order', 'footer_text', 'totalAmt', 'TenPercentTax', 'EightPercentTax'))->render());
        //     return $this->view('email-templates.customer-order-placed', compact('order_id'))
        //     ->attachData($pdf->Output('invoice.pdf', 'I'), 'invoice.pdf', [
        //         'mime' => 'application/pdf',
        //     ]);
        // }catch(\Exception $e){
        //     Log::error("Error building email: {$e->getMessage()}");
        // }
       
        try {
            $order_id = $this->order_id;
            $order = Order::with('delivery_address', 'details')->where('id', $order_id)->first();
            $orderDetails = collect($order->details);
            $EightPercentTax = $orderDetails->sum('eight_percent_tax');
            $TenPercentTax = $orderDetails->sum('ten_percent_tax');
            $totalAmt = (Helpers::calculateInvoice($order->id)) + $order->delivery_charge;
            $footer_text = BusinessSetting::where(['key' => 'footer_text'])->first();
        
            // Render the view content
            $viewContent = View::make('admin-views.order.latest_invoice', compact('order', 'footer_text', 'totalAmt', 'TenPercentTax', 'EightPercentTax'))->render();
        
            $mpdfConfig = [
                'mode' => 'utf-8',
                'format' => 'A4',
                'orientation' => 'P',
                'tempDir'   => base_path('storage/app/mpdf'),
            ];
        
            $pdf = new \Mpdf\Mpdf($mpdfConfig);
            $pdf->WriteHTML($viewContent);

            $invoiceFileName = 'invoice_' . $order->id . '.pdf';
        
            return $this->view('email-templates.customer-order-placed', compact('order_id'))
                ->subject('Order Confirmed: Thank You!') 
                ->attachData($pdf->Output($invoiceFileName, 'S'), $invoiceFileName, [
                    'mime' => 'application/pdf',
                ]);
        } catch (\Exception $e) {
            Log::error("Error building email: {$e->getMessage()}");
        }
    }
}

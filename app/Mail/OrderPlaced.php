<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PDF;

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
        $order_id = $this->order_id;
        $pdf = PDF::loadView('email-templates.customer-order-placed', compact('order_id'));
        return $this->view('email-templates.customer-order-placed', compact('order_id'))
            ->attachData($pdf->output(), 'invoice.pdf', [
                'mime' => 'application/pdf',
            ]);
        // return $this->view('email-templates.customer-order-placed', ['order_id' => $order_id]);
    }
}

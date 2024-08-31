<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Model\Product;

class ProductBackInStock extends Mailable
{
    use Queueable, SerializesModels;
    public $productname;
    public $userid;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($userid, $productname)
    {
        $this->userid = $userid;
        $this->productname = $productname;
    }

    
    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {   $userid= $this->userid;
        $productname  = $this->productname;
        $subject = 'Product Back in Stock: ' .  $this->productname;

        return $this->view('email-templates.product-back-in-stock',compact('productname','userid'))
            ->subject($subject);
    }
}

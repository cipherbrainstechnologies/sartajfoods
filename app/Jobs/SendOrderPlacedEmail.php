<?php

namespace App\Jobs;

use App\Mail\OrderPlaced;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendOrderPlacedEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $orderId;
    protected $customerEmail;

    public function __construct($orderId, $customerEmail)
    {
        $this->orderId = $orderId;
        $this->customerEmail = $customerEmail;
    }

    public function handle()
    {
        \Log::info('Job Call Start');
        
        try {
            // Attempt to send the email
            Mail::to($this->customerEmail)->send(new OrderPlaced($this->orderId));
            \Log::info('Email sent successfully');
        } catch (\Exception $e) {
            // Log any exceptions that occur during email sending
            \Log::error('Error sending email: ' . $e->getMessage());
        }
        
        \Log::info('Job Call End');
    }
}

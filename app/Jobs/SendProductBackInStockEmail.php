<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Model\product;
use App\Mail\ProductBackInStock;
class SendProductBackInStockEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userEmail;
    protected $productname;
    protected $userId;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($userId,$userEmail, $productname)
    {
        $this->userId = $userId;
        $this->userEmail = $userEmail;
        $this->productname = $productname;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        \Log::info('Job Call Start');
        
        try {
            // Attempt to send the email
            Mail::to($this->userEmail)->send(new ProductBackInStock($this->userId,$this->productname));
            \Log::info('Email sent successfully');
        } catch (\Exception $e) {
            // Log any exceptions that occur during email sending
            \Log::error('Error sending email: ' . $e->getMessage());
        }
        
        \Log::info('Job Call End');
    }
}

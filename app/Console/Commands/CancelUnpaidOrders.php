<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use App\Model\Order;
use App\Model\OrderHistory;
use Carbon\Carbon;

class CancelUnpaidOrders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'orders:cancel-unpaid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel unpaid PayPal orders that are pending for more than 5 minutes';

    /**
     * Execute the console command.
     *
     * @return int
     */

    public function handle()
    {

       Log::info('CancelUnpaidOrders command is running.');

       $twentyMinutesAgo = Carbon::now()->subMinutes(20);
       $tenMinutesAgo = Carbon::now()->subMinutes(10);
       Log::info($tenMinutesAgo);
        // Fetch orders from the last twenty minutes
       $orders = Order::where('payment_method', 'paypal')
       ->where('payment_status', 'unpaid')
       ->where('order_status', 'pending')
       ->where('created_at', '>=', $twentyMinutesAgo)
       ->get();

       foreach ($orders as $order) {
        if ($order->created_at  > $tenMinutesAgo) {
                // Update order status
            $order->order_status = 'canceled';
            $order->save();

                // Add entry to order history
            $description = 'Order has been cancelled due to non-payment.';
            OrderHistory::create([
                'order_id' => $order->id,
                'comment' => $description,
                'is_customer_notify' => 0, 
                'status' => 'canceled',
            ]);

                // Log or perform additional actions if needed
            Log::info('Order ID ' . $order->id . ' has been cancelled due to non-payment.');
        }
    }

    $this->info('Unpaid PayPal orders older than 10 minutes have been cancelled.');
    $this->info($twentyMinutesAgo);
    $this->info($tenMinutesAgo);
    
}
}

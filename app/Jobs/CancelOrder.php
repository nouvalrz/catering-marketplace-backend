<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Orders;
use App\Models\Customer;
use Kutia\Larafirebase\Facades\Larafirebase;

class CancelOrder implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Orders $order)
    {
        //
        $this->order = $order;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        if($this->order->status == "PAID"){
            $this->order->status = "CANCEL_BY_SYSTEM";
            $this->order->save();

            if($this->order->use_balance != null){
                $customer = Customer::find($this->order->customer_id);
                $customer->balance = $customer->balance + $this->order->use_balance;
            }

            $user = $customer->user()->first();
            Larafirebase::withTitle('Dibatalkan Otomatis')->withBody("Maaf pesanan {$this->order->invoice_number} dibatalkan otomatis, karena katering belum konfirmasi!")->withAdditionalData([
                'type' => 'CANCEL_BY_SYSTEM',
            ])->sendNotification($user->fcm_token);
        }
    }
}

<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputArgument;
use App\Models\Orders;

class AutoCancelOrder extends Command
{

    public $orderId;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'order:cancel {orderId}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cancel order when its still not approved after 6 hours';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        // $this->orderId = $orderId;
    }

    // public function configure()
    // {
    //     $this->argument('orderId', InputArgument::REQUIRED, 'Deskripsi argumen');
    // }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $orderId = $this->argument('orderId');
        $order = Orders::find($orderId);

        if($order->status == "PAID"){
            $order->status = "CANCEL_BY_SYSTEM";
            $order->save();
        }
    }
}

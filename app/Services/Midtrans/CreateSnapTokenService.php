<?php

namespace App\Services\Midtrans;

use Midtrans\Snap;

class CreateSnapTokenService extends Midtrans
{
    protected $order;

    public function __construct($order)
    {
        parent::__construct();

        $this->order = $order;
    }

    public function getSnapToken()
    {
        $itemDetails = [];
        foreach ($this->order->orderDetails()->get() as $product){
            $itemDetails[] = [
                'id' => $product->product_id,
                'price' => $product->price,
                'quantity' => $product->quantity,
                'name' => $product->product()->get()->first()->name
            ];
        }

        $itemDetails[] = [
            'id' => 9999,
            'price' => $this->order->delivery_cost,
            'quantity' => 1,
            'name' => "Delivery Cost"
        ];

        $params = [
            'transaction_details' => [
                'order_id' => $this->order->id,
                'gross_amount' => $this->order->total_price,
            ],
            'item_details' => $itemDetails,
            'customer_details' => [
                'first_name' => $this->order->customer()->get()->first()->user()->get()->first()->name,
                'email' => $this->order->customer()->get()->first()->user()->get()->first()->email,
                'phone' =>  $this->order->customer()->get()->first()->phone,
            ]
        ];

        $snapToken = Snap::getSnapToken($params);

        return $snapToken;
    }
}

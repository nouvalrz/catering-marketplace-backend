<?php

namespace App\Services\Midtrans;

use Carbon\Carbon;
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

        if($this->order->diskon != null){
            $discountDecode = json_decode($this->order->diskon);
            if($discountDecode->persenan != 0){
                $itemDetails[] = [
                    'id' => 9998,
                    'price' => -((int)$discountDecode->jumlah),
                    'quantity' => 1,
                    'name' => "Discount"
                ];
            }
        }

        if($this->order->use_balance != 0){
            $itemDetails[] = [
                'id' => 99999,
                'price' => -($this->order->use_balance),
                'quantity' => 1,
                'name' => "Pakai Saldo"
            ];
        }

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

    public function getSnapTokenForSubs()
    {

        $itemDetails = [];
//        foreach ($this->order->orderDetails()->get() as $product){
//            $itemDetails[] = [
//                'id' => $product->product_id,
//                'price' => $product->price,
//                'quantity' => $product->quantity,
//                'name' => $product->product()->get()->first()->name
//            ];
//        }
        foreach ($this->order->subsOrderDetails() as $key => $orderDetails){
            Carbon::setLocale('id');
            $dateParse = Carbon::parse($key)->isoFormat('D MMMM Y');
            $itemDetail = [
                'id' => $orderDetails[0]->id,
                'price' => 0,
                'quantity' => 1,
                'name' => "Pesanan tanggal $dateParse"
            ];
            foreach ($orderDetails as $orderDetail){
                $itemDetail["price"] += $orderDetail->price * $orderDetail->quantity;
            }
            $itemDetails[] = $itemDetail;
        }

        $itemDetails[] = [
            'id' => 9999,
            'price' => $this->order->delivery_cost,
            'quantity' => 1,
            'name' => "Delivery Cost"
        ];

        if($this->order->use_balance != 0){
            $itemDetails[] = [
                'id' => 99999,
                'price' => -($this->order->use_balance),
                'quantity' => 1,
                'name' => "Pakai Saldo"
            ];
        }


        if($this->order->diskon != null){
            $discountDecode = json_decode($this->order->diskon);
            if($discountDecode->persenan != 0){
                $itemDetails[] = [
                    'id' => 9998,
                    'price' => -((int)$discountDecode->jumlah),
                    'quantity' => 1,
                    'name' => "Discount"
                ];
            }
        }

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

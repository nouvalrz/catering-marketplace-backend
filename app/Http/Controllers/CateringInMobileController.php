<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Catering;
use App\Models\Orders;
use Illuminate\Http\Request;

class CateringInMobileController extends Controller
{
    //
    public function getDashbord(Request $request){
        $user = auth()->user();
//        dd($user->id);
        $catering = Catering::where('user_id', $user->id)->get()->first();

        $cateringOrders = Orders::where('catering_id', $catering->id)->orderBy('created_at', 'DESC')->get();

        $orderList = [];

        foreach ($cateringOrders as $order){
            $orderTemp = [];
            $orderQuanity = 0;
            $itemSummary = [];
            $orderDetails = $order->orderDetails()->get();

            foreach ($orderDetails as $orderDetail){
                $orderQuanity += $orderDetail->quantity;
            }

            foreach ($orderDetails as $index=>$orderDetail){
                if($index == 3){
                    break;
                }
                $itemSummary[] = $orderDetail->product()->get()->first()->name;
            }


            $orderTemp['id'] = $order->id;
            $orderTemp['order_type'] = $order->order_type;
            $orderTemp['start_date'] = $order->start_date;
            $orderTemp['end_date'] = $order->end_date;
            $orderTemp['order_status'] = $order->status;
            $orderTemp['use_balance'] = $order->use_balance;
            $orderTemp['order_quantity'] = $orderQuanity;
            $orderTemp['item_summary'] = join(", ", $itemSummary);
            $orderTemp['total_price'] = $order->total_price;

            $orderList[] = $orderTemp;
        }

//        return response()->json(["orders" => $orderList]);

        return response()->json([
           'catering'=> $catering,
           'orders' => $orderList
        ]);
    }
}

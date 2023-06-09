<?php

namespace App\Http\Controllers;

use App\Models\Complaints;
use App\Models\Customer;
use App\Models\Catering;
use App\Models\Orders;
use App\Models\ProductOptionDetail;
use App\Models\Reviews;
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

    public function showPreOrder($id){
        $order = Orders::find($id);

        $address = $order->customerAddresses()->get()->first();

        $productsRaws = $order->orderDetails()->with('product')->get();

        $products = [];

        $review = Reviews::where('order_id', $order->id)->first();
        $complaint = Complaints::with('images')->where('orders_id', $order->id)->first();
        $customer = Customer::find($order->customer_id);


        foreach ($productsRaws as $index=>$productsRaw){
            $productOptionSummary = [];

            $productOptions =  $productsRaw->productOptions()->get();

//            if($index == 0){
//                $catering = Catering::with('village')->where('id', $productsRaw->product->catering_id)->get()->first();
//                $customerName = $catering->name;
//                $customerPhone = $catering->phone;
//                $customerAddress = $catering->village->name;
//                $cateringOriginalPath = $catering->image;
//                $customerId = $catering->id;
//            }

            if($productOptions){
                foreach ($productOptions as $productOption){
                    $productOptionName = ProductOptionDetail::find($productOption->product_option_detail_id)->option_choice_name;
                    $productOptionSummary[] = $productOptionName;
                }
            }


            $product["id"] = $productsRaw->product->id;
            $product["name"] = $productsRaw->product->name;
            $product["quantity"] = $productsRaw->quantity;
            $product["price"] = $productsRaw->price;
            $product["image"] = $productsRaw->product->image;
            $product["product_option_summary"] = join(", ", $productOptionSummary);

            $products[] = $product;
        }

        $orderJson = [
            "id" => $order->id,
            "order_type" => $order->order_type,
            "invoice_number" => $order->invoice_number,
            "address" => $address,
            "delivery_datetime" => $order->start_date,
            "products" => $products,
            "subtotal" => $order->total_price - $order->delivery_cost,
            "delivery_price" => $order->delivery_cost,
            "total_price" => $order->total_price,
            "payment_expiry" =>$order->payment_expiry,
            "use_balance" =>$order->use_balance,
            "order_status" => $order->status,
            "created_at" =>$order->created_at,
            "discount" => $order->diskon,
            "customer" => $customer

        ];

        if($review){
            $orderJson['review'] = $review;
        }
        if($complaint){
            $orderJson['complaint'] = $complaint;
        }

        return response()->json(["order" => $orderJson]);
    }

}

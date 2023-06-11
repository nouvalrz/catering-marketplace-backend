<?php

namespace App\Http\Controllers;

use App\Models\Complaints;
use App\Models\Customer;
use Illuminate\Support\Facades\Storage;
use App\Models\Catering;
use App\Models\Orders;
use App\Models\ProductOptionDetail;
use App\Models\Reviews;
use Illuminate\Http\Request;
// use Artisan;
// use Illuminate\Console\Scheduling\Schedule;
// use Illuminate\Console\Command;
// use Illuminate\Support\Facades\Artisan;

use App\Jobs\CancelOrder;
// use Illuminate\Support\Facades\Artisan;

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
    public function showSubsOrder($id){
        $order = Orders::find($id);

        $address = $order->customerAddresses()->get()->first();

//        $productsRaws = $order->orderDetails()->with('product')->get();
//
//        $products = [];
        $ordersFix = array();

        $orders = $order->orderDetails()->with('product')->get()->groupBy('delivery_datetime')->all();


        foreach ($orders as $date => $orderSingle){
            $orderRaw = array();
            $orderRaw["delivery_datetime"] = $date;
            $orderRaw["subtotal_price"] = 0;

            $complaint = Complaints::with('images')->where('orders_id', $order->id)->where('delivery_datetime', $date)->get();

            if(count($complaint) !== 0){
                $orderRaw['complaint'] = $complaint->first();
            }

            foreach ($orderSingle as $orderDetail){
                $orderProductDetail = array();
                $orderProductDetail["id"] = $orderDetail["product"]->id;
                $orderProductDetail["name"] = $orderDetail["product"]->name;
                $orderProductDetail["quantity"] = $orderDetail["quantity"];
                $orderProductDetail["price"] = $orderDetail["price"];
                $orderProductDetail["custom_desc"] = $orderDetail["custom_desc"];
                $orderProductDetail["image"] = $orderDetail["product"]->image;

                $orderRaw["subtotal_price"] += $orderDetail["price"];
                $orderRaw["status"] = $orderDetail["status"];
                $orderRaw["products"][] = $orderProductDetail;
            }
            $ordersFix[] = $orderRaw;
        }

        $review = Reviews::where('order_id', $order->id)->first();
        $complaint = Complaints::with('images')->where('orders_id', $order->id)->first();
        $customer = Customer::find($order->customer_id);



        $orderJson = [
            "id" => $order->id,
            "order_type" => $order->order_type,
            "invoice_number" => $order->invoice_number,
            "address" => $address,
            "delivery_datetime" => $order->start_date,
            "orders" => $ordersFix,
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

    public function changeStatusOrder(Request $request){
        request()->validate([
            "orderId" => 'required',
            "newStatus" => 'required'
        ]);

        $order = Orders::find(request('orderId'));

        $order->status = request('newStatus');
        $order->save();

        if(request('newStatus') == "NOT_APPROVED"){
            $customer = Customer::find($order->customer_id);
            $customer->balance = $customer->balance + $order->total_price;
            $customer->save();
        }

        return response()->json(["order" => $order]);
    }

    public function changeStatusSubsOrder(Request $request){
        request()->validate([
            "orderId" => 'required',
            "newStatus" => 'required'
        ]);

        $order = Orders::find(request('orderId'));

        $order->status = request('newStatus');
        $order->save();

        if(request('newStatus') == "NOT_APPROVED"){
            $customer = Customer::find($order->customer_id);
            $customer->balance = $customer->balance + $order->total_price;
            $customer->save();
        }

        return response()->json(["order" => $order]);
    }

    public function changeStatusOrderOneDay(Request $request){
        $orderDetail = Orders::find(request('order_id'))->orderDetails()->get();
        $selectedOrder = $orderDetail->where('delivery_datetime', request('delivery_datetime'));
        foreach ($selectedOrder as $value){
            $value->status = "sending";
            $value->save();
        }
        return response()->json($selectedOrder);
    }


//     public function setCancel(){

//         $order = Orders::find(7009);
//         CancelOrder::dispatch($order)
//                     ->delay(now()->addMinutes(2));

//         // $schedule = new Schedule(new Command);

//         // // Tambahkan tugas ke Task Scheduler
//         // $schedule->command('order:cancel 7009')->when(function (){
//         //     return Carbon::create(2023,6,10,19,55)->isPast();
//         // })->everyMinute()->appendOutputTo(storage_path('logs/inspire.log'));

// // // Store the schedule in temporary storage
// // $temporaryFilePath = 'temporary_schedule.json';
// // Storage::put($temporaryFilePath, json_encode($schedule->events()));

// // // Run the added tasks
// // Artisan::call('schedule:run', [
// //     '--location' => $temporaryFilePath,
// // ]);

// // // Delete the temporary storage file
// // Storage::delete($temporaryFilePath);

//         // Artisan::call('schedule:run', [
//         //     '--schedule' => serialize($schedule)
//         // ]);
//         // $schedule->call(function () {
//         //     // Lakukan sesuatu
//         // })->everyTenMinutes();

//         // Jalankan tugas-tugas yang telah ditambahkan
//         // $schedule->run();
//         // return response()->json($schedule);
//     }
}

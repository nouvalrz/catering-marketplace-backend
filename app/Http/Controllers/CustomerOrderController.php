<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\Complaints;
use App\Models\Customer;
use App\Models\CustomerAddresses;
use App\Models\OrderDetails;
use App\Models\OrderProductOption;
use App\Models\Orders;
use App\Models\ProductOptionDetail;
use App\Models\Reviews;
use App\Models\User;
use App\Models\Village;
use App\Notifications\SendPushNotification;
use App\Services\Firebase\PaymentNotification;
use App\Services\Midtrans\CallbackService;
use App\Services\Midtrans\CreateSnapTokenService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kutia\Larafirebase\Facades\Larafirebase;
use Midtrans\Notification;

class CustomerOrderController extends Controller
{
    //
    public function createPreOrder(Request $request){
//        return $request;
        $user = auth()->user();
//        dd($user->id);
        $customer = Customer::where('user_id', $user->id)->get()->first();

        $address = $this->getAddress($request, $customer, $user);

//        dd($address->id);

        $invoiceNumber = "INV/" . Carbon::now()->format('dmy') . "/PO/" . mt_rand(10000000, 99999999);

        if(request('use_balance') > 0){
            $customer->balance -= request('use_balance');
            $customer->save();
        }

        $order = Orders::create([
            'invoice_number' => $invoiceNumber,
            'customer_id' => $customer->id,
            'customer_addresses_id' => $address->id,
            'delivery_cost' => request('delivery_price'),
            'total_price' => request('total_price'),
            'order_type' => "preorder",
            'start_date' => request('delivery_date'),
            'end_date' => request('delivery_date'),
            'catering_id' => request('catering_id'),
            'status' => 'PENDING',
            'snap_token' => "s",
            'cancele_at' => "2023-04-07 12:00:00",
            'use_balance' => request('use_balance') ?? null,
            'diskon' => request('discount') ? stripslashes(request('discount')) : null
        ]);
        
        $order->customer_addresses_id = $address->id;
        $order->save();

        $products = request('products');
        foreach ($products as $product){
            $productOptions = $product['product_options'];
            $orderDetail = OrderDetails::create([
                'orders_id' => $order->id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'delivery_datetime' => request('delivery_date'),
                'status' => "PENDING",
            ]);
            foreach ($productOptions as $productOption){
                $productOptionDetails = $productOption['product_option_details'];
                foreach ($productOptionDetails as $productOptionDetail){
                    OrderProductOption::create([
                        'order_detail_id' => $orderDetail->id,
                        'product_option_id' => $productOption['id'],
                        'product_option_detail_id' => $productOptionDetail['id']
                    ]);
                }
            }
        }
        $midtrans = new CreateSnapTokenService($order);
        $snapToken = $midtrans->getSnapToken();
        $order->snap_token = $snapToken;
        $order->save();

        return response()->json($order);
//        return $address;
    }

    public function createSubsOrder(Request $request){
        $user = auth()->user();
//        dd($user->id);
        $customer = Customer::where('user_id', $user->id)->get()->first();

        $address = $this->getAddress($request, $customer, $user);

        if(request('use_balance') > 0){
            $customer->balance -= request('use_balance');
            $customer->save();
        }

        $invoiceNumber = "INV/" . Carbon::now()->format('dmy') . "/SUBS/" . mt_rand(10000000, 99999999);
        $order = Orders::create([
            'invoice_number' => $invoiceNumber,
            'customer_id' => $customer->id,
            'customer_addresses_id' => $address->id,
            'delivery_cost' => request('delivery_cost'),
            'total_price' => request('total_price'),
            'use_balance' => request('use_balance'),
            'order_type' => "subs",
            'start_date' => request('start_date'),
            'end_date' => request('end_date'),
            'catering_id' => request('catering_id'),
            'status' => 'PENDING',
            'snap_token' => "s",
            'cancele_at' => "2023-04-07 12:00:00",
            'diskon' => request('discount') ? stripslashes(request('discount')) : null
        ]);
        
        $order->customer_addresses_id = $address->id;
        $order->save();

        $orderDetails = request('order_details');

        foreach ($orderDetails as $orderDetail){
            $orderDetailCreated = OrderDetails::create([
                'orders_id' => $order->id,
                'product_id' => $orderDetail['product_id'],
                'quantity' => $orderDetail['quantity'],
                'price' => $orderDetail['price'],
                'delivery_datetime' => $orderDetail["delivery_datetime"],
                'custom_desc' => $orderDetail['custom_desc'] == null ? "NULL" : $orderDetail['custom_desc'],
                'status' => "PENDING",
            ]);
        }

        $midtrans = new CreateSnapTokenService($order);
        $snapToken = $midtrans->getSnapTokenForSubs();
        $order->snap_token = $snapToken;
        $order->save();

        return response()->json($order);
    }

    public function receive()
    {
        $callback = new CallbackService;

        if (true) {
            $notification = $callback->getNotification();
            $order = $callback->getOrder();

            if ($callback->isSuccess()) {
                Orders::where('id', $notification->order_id)->update([
                    'status' => 'PAID',
                ]);
                $user = Orders::where('id', $notification->order_id)->first()->customer()->first()->user()->get()->first();

                Larafirebase::withTitle('Pembayaran Diterima')->withBody("Terimakasih pesanan ada dengan ID {$notification->order_id} telah dibayar!")->withAdditionalData([
                    'type' => 'PAYMENT_SUCCESS',
                ])->sendNotification($user->fcm_token);
            }

            if ($callback->isExpire()) {
                Orders::where('id', $notification->order_id)->update([
                    'status' => 'VOID',
                ]);
            }

            if ($callback->isCancelled()) {
                Orders::where('id', $notification->order_id)->update([
                    'status' => "VOID",
                ]);
            }

            if ($callback->isPending()) {
                Orders::where('id', $notification->order_id)->update([
                    'status' => "UNPAID",
                    'payment_expiry' => Carbon::parse($notification->expiry_time)->addHour()
                ]);
            }

            return response()
                ->json([
                    'success' => true,
                    'message' => 'Notification successfully processed',
                ]);


        } else {
            return response()
                ->json([
                    'error' => true,
                    'message' => 'Signature key not verified',
                ], 403);
        }
    }

    public function showSnapToken($id)
    {
        $order = Orders::find($id);
        $snapToken = $order->snap_token;
        return view('orders.show', compact('order', 'snapToken'));
    }

    private function getAddress(Request $request, Customer $customer, User $user){
        if(request('address.id') == null){
            $address = CustomerAddresses::create([
                'customer_id' => $customer->id,
                'recipient_name' => $user->name,
                'address' => request('address.address'),
                'latitude' => request('address.latitude'),
                'longitude' => request('address.longitude'),
                'phone' => $customer->phone
            ]);
            return $address;
        }else{
            $address = CustomerAddresses::find(request('address.id'));
            return $address;
        }
    }

    public function index(){
        $user = auth()->user();
        $customer = Customer::where('user_id', $user->id)->get()->first();

        $orders = $customer->orders()->orderBy('id', 'desc')->get();

//        dd($orders);

        $orderList = [];

        foreach ($orders as $order){
            $orderTemp = [];
            $orderQuanity = 0;
            $itemSummary = [];
            $cateringName = Catering::find($order->catering_id)->name;
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
            $orderTemp['catering_name'] = $cateringName;
            $orderTemp['order_quantity'] = $orderQuanity;
            $orderTemp['item_summary'] = join(", ", $itemSummary);
            $orderTemp['total_price'] = $order->total_price;

            $orderList[] = $orderTemp;
        }

        return response()->json(["orders" => $orderList]);
    }

    public function showPreOrder($id){
        $order = Orders::find($id);

        $address = $order->customerAddresses()->get()->first();

        $productsRaws = $order->orderDetails()->with('product')->get();

        $products = [];

        $review = Reviews::where('order_id', $order->id)->first();
        $complaint = Complaints::with('images')->where('orders_id', $order->id)->first();

        $cateringName = "";
        $cateringPhone = "";
        $cateringLocation = "";
        $cateringOriginalPath = "";
        $cateringId = '';

        foreach ($productsRaws as $index=>$productsRaw){
            $productOptionSummary = [];

            $productOptions =  $productsRaw->productOptions()->get();

            if($index == 0){
                $catering = Catering::with('village')->where('id', $productsRaw->product->catering_id)->get()->first();
                $cateringName = $catering->name;
                $cateringPhone = $catering->phone;
                $cateringLocation = $catering->village->name;
                $cateringOriginalPath = $catering->image;
                $cateringId = $catering->id;
            }

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
            "catering_name" => $cateringName,
            "catering_phone" => $cateringPhone,
            "catering_location" => $cateringLocation,
            "image" => $cateringOriginalPath,
            "catering_id" => $cateringId
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

        $cateringName = "";
        $cateringPhone = "";
        $cateringLocation = "";
        $cateringOriginalPath = "";
        $cateringId = '';

        $address = $order->customerAddresses()->get()->first();

        $orders = $order->orderDetails()->with('product')->get()->groupBy('delivery_datetime')->all();

//        return response()->json($orders);

        $review = Reviews::where('order_id', $order->id)->first();

        $ordersFix = array();


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

//        return response()->json($ordersFix);



//        foreach ($productsRaws as $index=>$productsRaw){
//            $productOptionSummary = [];
//
//            $productOptions =  $productsRaw->productOptions()->get();
//
//            if($index == 0){
//                $catering = Catering::with('village')->where('id', $productsRaw->product->catering_id)->get()->first();
//                $cateringName = $catering->name;
//                $cateringPhone = $catering->phone;
//                $cateringLocation = $catering->village->name;
//                $cateringOriginalPath = $catering->image;
//                $cateringId = $catering->id;
//            }
//
//            if($productOptions){
//                foreach ($productOptions as $productOption){
//                    $productOptionName = ProductOptionDetail::find($productOption->product_option_detail_id)->option_choice_name;
//                    $productOptionSummary[] = $productOptionName;
//                }
//            }
//
//
//            $product["id"] = $productsRaw->product->id;
//            $product["name"] = $productsRaw->product->name;
//            $product["quantity"] = $productsRaw->quantity;
//            $product["price"] = $productsRaw->price;
//            $product["image"] = $productsRaw->product->image;
//            $product["product_option_summary"] = join(", ", $productOptionSummary);
//
//            $products[] = $product;
//        }

//        return response()->json($order);

        $catering = Catering::find($order->catering_id);
        $cateringName = $catering->name;
        $cateringPhone = $catering->phone;
        $cateringLocation = $catering->village->name;
        $cateringOriginalPath = $catering->image;
        $cateringId = $catering->id;

        $orderJson = [
            "id" => $order->id,
            "catering_name" => $cateringName,
            "catering_phone" => $cateringPhone,
            "catering_location" => $cateringLocation,
            "image" => $cateringOriginalPath,
            "catering_id" => $cateringId,
            "order_type" => $order->order_type,
            "invoice_number" => $order->invoice_number,
            "address" => $address,
            "start_date" => $order->start_date,
            "end_date" => $order->end_date,
            "use_balance" => $order->use_balance,
//            "products" => $products,
            "subtotal" => $order->total_price - $order->delivery_cost,
            "delivery_price" => $order->delivery_cost,
            "total_price" => $order->total_price,
            "payment_expiry" =>$order->payment_expiry,
            "order_status" => $order->status,
            "created_at" =>$order->created_at,
            "discount" => $order->diskon,
            "orders" => $ordersFix,
        ];

        if($review){
            $orderJson['review'] = $review;
        }

        return response()->json(["order" => $orderJson]);

    }

    public function getOrderPaidStatus($id){
        $orderPaidStatus = Orders::select('status')->where('id', $id)->get()->first();
        return response()->json($orderPaidStatus);
    }

    public function setOrderToAccepted($id){
        $order = Orders::find($id);
        $order->status = "ACCEPTED";
        $order->save();
        return response()->json($order);
    }

    public function setSubsOrdertoAccepted(Request $request){
        $orderDetail = Orders::find(request('order_id'))->orderDetails()->get();
        $selectedOrder = $orderDetail->where('delivery_datetime', request('delivery_datetime'));
        foreach ($selectedOrder as $value){
            $value->status = "delivered";
            $value->save();
        }
        return response()->json($selectedOrder);
    }
}

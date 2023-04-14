<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\Customer;
use App\Models\CustomerAddresses;
use App\Models\OrderDetails;
use App\Models\OrderProductOption;
use App\Models\Orders;
use App\Models\ProductOptionDetail;
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

        $order = Orders::create([
            'customer_id' => $customer->id,
            'customer_addresses_id' => $address->id,
            'delivery_type' => 'DELIVERY',
            'delivery_cost' => request('delivery_price'),
            'total_price' => request('total_price'),
            'order_type' => "PREORDER",
            'start_date' => request('delivery_date'),
            'end_date' => request('delivery_date'),
            'paid_status' => 'CREATED',
            'snap_token' => "s",
            'note' => 'n',
            'cancele_at' => "2023-04-07 12:00:00"
        ]);

        $products = request('products');
        foreach ($products as $product){
            $productOptions = $product['product_options'];
            $orderDetail = OrderDetails::create([
                'orders_id' => $order->id,
                'product_id' => $product['id'],
                'quantity' => $product['quantity'],
                'price' => $product['price'],
                'package_number' => 1,
                'delivery_datetime' => request('delivery_date'),
                'status' => 0,
            ]);
            foreach ($productOptions as $productOption){
                $productOptionDetails = $productOption['product_option_details'];
                foreach ($productOptionDetails as $productOptionDetail){
                    OrderProductOption::create([
                        'order_details_id' => $orderDetail->id,
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

    public function receive()
    {
        $callback = new CallbackService;

        if (true) {
            $notification = $callback->getNotification();
            $order = $callback->getOrder();

            if ($callback->isSuccess()) {
                Orders::where('id', $notification->order_id)->update([
                    'paid_status' => 'PAID',
                ]);
                $user = Orders::where('id', $notification->order_id)->first()->customer()->first()->user()->get()->first();

                Larafirebase::withTitle('Pembayaran Diterima')->withBody("Terimakasih pesanan ada dengan ID {$notification->order_id} telah dibayar!")->withAdditionalData([
                    'type' => 'PAYMENT_SUCCESS',
                ])->sendNotification($user->fcm_token);
            }

            if ($callback->isExpire()) {
                Orders::where('id', $notification->order_id)->update([
                    'paid_status' => 'VOID',
                ]);
            }

            if ($callback->isCancelled()) {
                Orders::where('id', $notification->order_id)->update([
                    'paid_status' => "VOID",
                ]);
            }

            if ($callback->isPending()) {
                Orders::where('id', $notification->order_id)->update([
                    'paid_status' => "UNPAID",
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

        $orderList = [];

        foreach ($orders as $order){
            $orderTemp = [];
            $orderQuanity = 0;
            $itemSummary = [];
            $cateringName = $order->orderDetails()->get()->first()->product()->get()->first()->catering()->get()->first()->name;
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
            $orderTemp['paid_status'] = $order->paid_status;
            $orderTemp['order_status'] = $order->order_status;
            $orderTemp['catering_name'] = $cateringName;
            $orderTemp['order_quantity'] = $orderQuanity;
            $orderTemp['item_summary'] = join(", ", $itemSummary);
            $orderTemp['total_price'] = $order->total_price;

            $orderList[] = $orderTemp;
        }

        return response()->json(["orders" => $orderList]);
    }

    public function show($id){
        $order = Orders::find($id);

        $address = $order->customerAddresses()->get()->first();

        $productsRaws = $order->orderDetails()->with('product')->get();

        $products = [];

        $cateringName = "";
        $cateringPhone = "";
        $cateringLocation = "";
        $cateringOriginalPath = "";

        foreach ($productsRaws as $index=>$productsRaw){
            $productOptionSummary = [];

            $productOptions =  $productsRaw->productOptions()->get();

            if($index == 0){
                $catering = Catering::with('village')->where('id', $productsRaw->product->catering_id)->get()->first();
                $cateringName = $catering->name;
                $cateringPhone = $catering->phone;
                $cateringLocation = $catering->village->name;
                $cateringOriginalPath = $catering->original_path;
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
            $product["original_path"] = $productsRaw->product->original_path;
            $product["product_option_summary"] = join(", ", $productOptionSummary);

            $products[] = $product;
        }

        $orderJson = [
            "id" => $order->id,
            "order_type" => $order->order_type,
            "invoice_number" => "INV/21912912912",
            "address" => $address,
            "delivery_datetime" => $order->start_date,
            "products" => $products,
            "subtotal" => $order->total_price - $order->delivery_cost,
            "delivery_price" => $order->delivery_cost,
            "total_price" => $order->total_price,
            "paid_status" => $order->paid_status,
            "payment_expiry" =>$order->payment_expiry,
            "order_status" => $order->order_status,
            "created_at" =>$order->created_at,
            "catering_name" => $cateringName,
            "catering_phone" => $cateringPhone,
            "catering_location" => $cateringLocation,
            "catering_original_path" => $cateringOriginalPath
        ];

        return response()->json(["order" => $orderJson]);

    }

    public function getOrderPaidStatus($id){
        $orderPaidStatus = Orders::select('paid_status')->where('id', $id)->get()->first();
        return response()->json($orderPaidStatus);
    }
}

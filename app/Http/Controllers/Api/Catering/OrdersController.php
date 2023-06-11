<?php

namespace App\Http\Controllers\Api\Catering;

use App\Models\Orders;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Models\Catering;
use App\Models\ComplaintImages;
use App\Models\Customer;
use App\Models\CustomerAddresses;
use App\Models\OrderDetails;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Kutia\Larafirebase\Facades\Larafirebase;
use Tymon\JWTAuth\Facades\JWTAuth;

class OrdersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get products
        // $products = Product::with('category')->when(request()->q,
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');

        $orders = Orders::with('customer:id,name,image')->where('status', 'like', '%' . request()->status . '%' );

        $orders = $orders->where('catering_id', $cateringId)->orderBy('start_date', 'desc')->when(request()->q,
        function($orders) {
            $orders = $orders->where('invoice_number', 'like', '%'. request()->q . '%')
                ->orWhereHas('customer',
            function($orders) {
                $orders = $orders->where('name', 'like', '%'. request()->q . '%')
                    ->where('status', 'like', '%' . request()->status . '%' );
            });
        })->latest()->paginate(request()->pages);
        //return with Api Resource
        return new OrderResource(true, 'List Data Orders', $orders);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $userId = auth()->guard('api_catering')->user()->id;
        // $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        // $orders = Orders::with(['catering:id,name,image', 'customer:id,name,image', 'customerAddresses:id,recipient_name,phone,address,latitude,longitude'])->where('status', 'like', '%' . request()->status . '%' );

        $orders = Orders::whereId($id)->with(['catering:id,name,image', 'customer:id,name,image', 'customerAddresses:id,recipient_name,phone,address,latitude,longitude', 'review', 'complaint'])->first();
        $ordersDetail = OrderDetails::where('orders_id', '=', $id)->with('product:id,name,price,weight,image')->get();
        if(!$ordersDetail){
            $ordersDetail = null;
        }
        // $customerAddressZipcode = CustomerAddresses::whereId($orders->customer_addresses_id)->value('zipcode');
        $orders->diskon = json_decode($orders->diskon);

        if($orders->complaint){
            $complaintImage = ComplaintImages::where('complaint_id', $orders->complaint->id)->get();
        }else{
            $complaintImage = null;
        }

        $orders->linkImageReview = asset('storage/reviews/');

        if($orders) {
            //response
            return response()->json([
                'success' => true,
                'message' => 'Detail Data Order',
                'data' => [
                    'order' => $orders,
                    'order_detail' => $ordersDetail,
                    'complaint_image' => $complaintImage,
                    'linkImageReview' => $complaintImage,
                ]
            ], 200);
        }
        //return failed with Api Resource
        return new OrderResource(false, 'Detail Data Product Tidak Ditemukan!', null);
    }


    public function listProducts($id)
    {
        // $userId = auth()->guard('api_catering')->user()->id;
        // $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        if(!$id){
            return new OrderResource(true, 'Data List Product Tidak Ada!', null);
        }
        // $orders = Orders::whereId($id)->first();
        $ordersDetail = OrderDetails::where('orders_id', '=', $id)->get();

        //return failed with Api Resource
        if($ordersDetail){
            return new OrderResource(true, 'Data List Product!', $ordersDetail);
        }
        return new OrderResource(true, 'Data List Product Tidak Ada!', null);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Orders $order)
    {

    }


    public function changeStatus(Request $request, $id)
    {
        // dd($request->status);

        if($request->tanggal){
            // $order      = Orders::findOrFail($id);
            $orderDetails = OrderDetails::where('orders_id', $id)->where('delivery_datetime', $request->tanggal)->get();

            foreach($orderDetails as $orderDetail){
                $orderDetail->status = 'sending';
                $orderDetail->save();
            }



            return new ProductResource(true, 'Data Product Berhasil!', $orderDetail);

        }else{

            $order      = Orders::findOrFail($id);
            $order->status = $request->status;
            $order->save();

            if($request->status == "PROCESSED"){

                $user = Customer::find($order->customer_id)->user()->first();

                Larafirebase::withTitle('Pesanan Diterima Katering')->withBody("Pesanan {$order->invoice_number} telah diterima katering!")->withAdditionalData([
                    'type' => 'PROCESSED',
                ])->sendNotification($user->fcm_token);
            }

            if($request->status == "NOT_APPROVED"){
                $user = Customer::find($order->customer_id)->user()->first();

                Larafirebase::withTitle('Pesanan Ditolak Katering')->withBody("Pesanan {$order->invoice_number} ditolak katering. Uang akan dikembalikan ke saldo Kateringku!")->withAdditionalData([
                    'type' => 'PROCESSED',
                ])->sendNotification($user->fcm_token);
            }

            if($request->status == 'SEND'){
                $orderDetails = OrderDetails::where('orders_id', $id)->get();

                // foreach($orderDetails as $orderDetail){
                foreach($orderDetails as $orderDetail){
                    $orderDetail->status = 'sending';
                    $orderDetail->save();
                }

                $user = Customer::find($order->customer_id)->user()->first();

                Larafirebase::withTitle('Pesanan Sedang Diantar Katering')->withBody("Pesanan {$order->invoice_number} sedang diantar katering, mohon ditunggu!")->withAdditionalData([
                    'type' => 'SENT',
                ])->sendNotification($user->fcm_token);
            }
        }

        if($order) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil!', $order);
        }
        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Diupdate!', $order);
    }

    /**
     * Remove the specified resource from storage.
     *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Orders $order)
    {

    }

}

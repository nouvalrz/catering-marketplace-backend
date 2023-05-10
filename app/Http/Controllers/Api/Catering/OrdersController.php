<?php

namespace App\Http\Controllers\Api\Catering;

use App\Models\Orders;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;
use App\Http\Resources\ProductResource;
use App\Models\Catering;
use App\Models\Customer;
use App\Models\CustomerAddresses;
use App\Models\OrderDetails;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
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

        $orders = Orders::where('status', 'like', '%' . request()->status . '%' );

        $orders = $orders->where('catering_id', $cateringId)->orderBy('start_date', 'desc')->when(request()->q,
        function($orders) {
            $orders = $orders->where('invoice_number', 'like', '%'. request()->q . '%');
        })->latest()->paginate(request()->pages);
        //return with Api Resource
        return new OrderResource(true, 'List Data Orders', $orders);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

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
        
        $orders = Orders::whereId($id)->first();
        $ordersDetail = OrderDetails::where('orders_id', '=', $id)->get();
        $customerName = Customer::whereId($orders->customer_id)->value('name');
        $customerPhone = Customer::whereId($orders->customer_id)->value('phone');
        $customerAddressRecipient = CustomerAddresses::whereId($orders->customer_addresses_id)->value('recipient_name');
        $customerAddress = CustomerAddresses::whereId($orders->customer_addresses_id)->value('address');
        // $customerAddressZipcode = CustomerAddresses::whereId($orders->customer_addresses_id)->value('zipcode');

        if($orders) {
            //response
            return response()->json([
                'success' => true,
                'message' => 'Detail Data Order',
                'data' => [
                    'order' => $orders,
                    'customer' => [
                        'name' => $customerName,
                        'phone' => $customerPhone,
                        'address_recipient' => $customerAddressRecipient,
                        'address' => $customerAddress,
                        // 'address_zipcode' => $customerAddressZipcode,
                    ],
                    'order_detail' => $ordersDetail,
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
        
        // $orders = Orders::whereId($id)->first();
        $ordersDetail = OrderDetails::where('orders_id', '=', $id)->get();
        
        //return failed with Api Resource
        return new OrderResource(true, 'Data List Product!', $ordersDetail);
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
        $order      = Orders::findOrFail($id);
        $order->status = $request->status;
        $order->save();

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

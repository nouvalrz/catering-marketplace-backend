<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Orders;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use App\Http\Resources\CancelResource;
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
use Tymon\JWTAuth\Facades\JWTAuth;

class CancelsController extends Controller
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
        // $userId = auth()->guard('api_catering')->user()->id;
        // $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');

        
        if(request()->status){
            $cancelOrder = Orders::with('customer:id,name,image')->where('status', request()->status);
        }else{

            $cancelOrder = Orders::with('customer:id,name,image')->orWhere('status', 'REQUEST_CANCEL')->orWhere('status', 'CANCEL_BY_SYSTEM')->orWhere('status', 'APPROVED_CANCEL')->orWhere('status', 'CANCEL_REJECTED');
        }

        $cancelOrder = $cancelOrder->orderBy('start_date', 'desc')->when(request()->q,
        function($cancelOrder) {
            $cancelOrder = $cancelOrder->where('invoice_number', 'like', '%'. request()->q . '%')
                ->orWhereHas('customer', 
            function($cancelOrder) {
                $cancelOrder = $cancelOrder->where('name', 'like', '%'. request()->q . '%')
                    ->where('status', 'like', '%' . request()->status . '%' ); 
            });
        })->latest()->paginate(request()->pages);
        //return with Api Resource
        return new CancelResource(true, 'List Data Cancels', $cancelOrder);
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
        // $cancelOrder = Orders::with(['catering:id,name,image', 'customer:id,name,image', 'customerAddresses:id,recipient_name,phone,address,latitude,longitude'])->where('status', 'like', '%' . request()->status . '%' );
        
        $cancelOrder = Orders::whereId($id)->with(['catering:id,name,image', 'customer:id,name,image', 'customerAddresses:id,recipient_name,phone,address,latitude,longitude', 'review', 'complaint'])->first();
        $ordersDetail = OrderDetails::where('orders_id', '=', $id)->with('product:id,name,price,weight,image')->get();
        
        if(!$ordersDetail){
            $ordersDetail = null;
        }
        // $customerAddressZipcode = CustomerAddresses::whereId($cancelOrder->customer_addresses_id)->value('zipcode');
        $cancelOrder->diskon = json_decode($cancelOrder->diskon);
        
        if($cancelOrder->complaint){
            $complaintImage = ComplaintImages::where('complaint_id', $cancelOrder->complaint->id)->get();
        }else{
            $complaintImage = null;
        }

        if($cancelOrder) {
            //response
            return response()->json([
                'success' => true,
                'message' => 'Detail Data Order',
                'data' => [
                    'order' => $cancelOrder,
                    'order_detail' => $ordersDetail,
                    'complaint_image' => $complaintImage,
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
        
        // $cancelOrder = Orders::whereId($id)->first();
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

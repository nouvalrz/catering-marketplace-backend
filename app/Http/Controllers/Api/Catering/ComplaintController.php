<?php

namespace App\Http\Controllers\Api\Catering;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\ComplaintsResource;
use App\Models\Complaints;
use App\Models\Customer;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
{

    // var userID = auth()->guard('api_catering')->user()->id;
    // var cateringID =
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
        $complaints = Complaints::with('orders')->whereHas('orders',
            function($complaints) {
                $userId = auth()->guard('api_catering')->user()->id;
                $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
                $complaints = $complaints->where('catering_id', $cateringId);
                })->where('status', 'like', '%'. request()->status . '%')->when(request()->q,
                    function($complaints) {
                        $complaints = $complaints->whereHas('orders',
                            function($complaints) {
                                $complaints = $complaints->where('invoice_number', 'like', '%'. request()->q . '%');
                    });
        })->latest()->paginate(request()->pages);

        //return with Api Resource
        return new ComplaintResource(true, 'List Data Complaints', $complaints);
    }


    public function changeStatus(Request $request, $id)
    {
        // dd($request->status);
        $complaint      = Complaints::findOrFail($id);
        $complaint->status = $request->status;
        $complaint->save();

        if($complaint->solution_type == "refund" && $request->status == "approve"){
            $order = Orders::find($complaint->orders_id);
            $customer = Customer::find($order->customer_id);
            $customer->balance = $customer->balance + $order->total_price;
            $customer->save();
        }


        if($complaint) {
            //return success with Api Resource
            return new ComplaintResource(true, 'Data Product Berhasil!', $complaint);
        }
        //return failed with Api Resource
        return new ComplaintResource(false, 'Data Product Gagal Diupdate!', $complaint);
    }
}

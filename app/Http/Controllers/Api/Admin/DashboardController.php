<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiscountResource;
use App\Models\Discount;
use App\Models\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = auth()->guard('api_admin')->user()->id;
        $cateringId = DB::table('administrators')->where('user_id', $userId)->value('id');
        $order = Orders::where('catering_id', '=', $cateringId)->get();

        $pending = $order->where('status', '=', 'Menunggu Konfirmasi')->count();
        $notApprove = $order->where('status', '=', 'Tidak Disetujui')->count();
        $approve = $order->where('status', '=', 'Diproses')->count();
        $sending = $order->where('status', '=', 'Dikirim')->count();
        $received = $order->where('status', '=', 'Diterima')->count();
        $complain = $order->where('status', '=', 'Dikomplain')->count();

        if(request()->q == null){
            $year = date('Y');
        }else{
            $year = request()->q;
        }

        $transaction = DB::table('orders')
            ->addSelect(DB::raw('SUM(total_price) as total_price'))
            ->addSelect(DB::raw('MONTH(created_at) as month'))
            ->addSelect(DB::raw('MONTHNAME(created_at) as month_name'))
            ->addSelect(DB::raw('YEAR(created_at) as year'))
            ->whereYear('created_at', '=', $year)
            ->where('status', '=', 'Diterima')
            ->groupBy('month')
            ->orderByRaw('month ASC')
            ->get();

        if(count($transaction)){
            foreach($transaction as $result){
                $month_name[] = $result->month_name;
                $total_price[] = $result->total_price;
            }
        }else{
            $month_name[] = '';
            $total_price[] = '';
        }

        //response
        return response()->json([
            'success' => true,
            'message' => 'Statistik Data',
            'data' => [
                'count' => [
                    'pending' => $pending,
                    'notApprove' => $notApprove,
                    'approve' => $approve,
                    'sending' => $sending,
                    'received' => $received,
                    'complain' => $complain
                ],
                'chart' => [
                    'month_name' => $month_name,
                    'total_price' => $total_price
                ]
            ]
        ], 200);
        
        //get products
        // $products = Product::with('category')->when(request()->q,
        // $discounts = Discount::where('catering_id', $cateringId)->when(request()->q,
        // function($discounts) {
        //     $discounts = $discounts->where('title', 'like', '%'. request()->q . '%');
        // })->latest()->paginate(5);
        // //return with Api Resource
        // return new DiscountResource(true, 'List Data Discount', $discounts);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        $validator = Validator::make($request->all(), [
            // 'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'type' => 'required',
            'title' => 'required',
            // 'catering_id' => 'required',
            'description' => 'required',
            'percentage' => 'required',
            'minimum_spend' => 'required',
            'maximum_disc' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //upload image
        // $image = $request->file('image');
        // $image->storeAs('public/products', $image->hashName());
        //create product
        $discounts = Discount::create([
            // 'image' => $image->hashName(),
            'type' => $request->type,
            'title' => $request->title,
            // 'slug' => Str::slug($request->title, '-'),
            'catering_id' => $cateringId,
            // 'user_id' => auth()->guard('api_admin')->user()->id,
            'description' => $request->description,
            'percentage' => $request->percentage,
            'minimum_spend' => $request->minimum_spend,
            'maximum_disc' => $request->maximum_disc,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,

        ]);
        if($discounts) {
            //return success with Api Resource
            return new DiscountResource(true, 'Data Discounts Berhasil Disimpan!', $discounts);
        }
        //return failed with Api Resource
        return new DiscountResource(false, 'Data Discounts Gagal Disimpan!', null);
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
        
        $discounts = Discount::whereId($id)->first();
        if($discounts) {
            //return success with Api Resource
            return new DiscountResource(true, 'Detail Data Piscounts!', $discounts);
        }
        //return failed with Api Resource
        return new DiscountResource(false, 'Detail Data Discounts Tidak Ditemukan!', null);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $discounts = Discount::find($id);
        // dd($discounts);
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'title' => 'required',
            'description' => 'required',
            'percentage' => 'required',
            'minimum_spend' => 'required',
            'maximum_disc' => 'required',
            // 'start_date' => 'required',
            // 'end_date' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //check image update
        if ($request->file('image')) {
        //     //remove old image
        //     // Storage::disk('local')->delete('public/products/'.basename($product->image));
        //     //upload new image
        //     // $image = $request->file('image');
        //     // $image->storeAs('public/products', $image->hashName());
        //     //update product with new image
            $discounts->update([
                // 'image' => $image->hashName(),
                'type' => $request->type,
                'title' => $request->title,
                // 'slug' => Str::slug($request->title, '-'),
                'catering_id' => $cateringId,
                // 'user_id' => auth()->guard('api_admin')->user()->id,
                'description' => $request->description,
                'percentage' => $request->percentage,
                'minimum_spend' => $request->minimum_spend,
                'maximum_disc' => $request->maximum_disc,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,

            ]);
        }
        //update product without image
        // $discounts->update([
        //     'name' => $request->name,
        //     // 'slug' => Str::slug($request->title, '-'),
        //     'catering_id' => $cateringId,
        //     'description' => $request->description,
        //     'weight' => $request->weight,
        //     'price' => $request->price,
        //     'minimum_quantity' => $request->minimum_quantity,
        //     'maximum_quantity' => $request->maximum_quantity,
        //     'is_free_delivery' => $request->is_free_delivery,
        //     'is_hidden' => $request->is_hidden,
        //     'is_available' => $request->is_available,
        //     // 'image_id' => $cateringId,

        // ]);
        $discounts->update([
            'type' => $request->type,
            'title' => $request->title,
            'catering_id' => $cateringId,
            'description' => $request->description,
            'percentage' => $request->percentage,
            'minimum_spend' => $request->minimum_spend,
            'maximum_disc' => $request->maximum_disc,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,

        ]);
        // dd($discounts);
        if($discounts) {
            //return success with Api Resource
            return new DiscountResource(true, 'Data Discounts Berhasil Diupdate!', $discounts);
        }
        //return failed with Api Resource
        return new DiscountResource(false, 'Data Discounts Gagal Diupdate!', null);
    }
    
    /**
     * Remove the specified resource from storage.
     *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(int $id)
    {
        $discounts = Discount::find($id);
        //remove image
        // Storage::disk('local')->delete('public/products/'.basename($product->image));
        if($discounts->delete()) {
            //return success with Api Resource
            return new DiscountResource(true, 'Data Discount Berhasil Dihapus!', null);
        }
        //return failed with Api Resource
        return new DiscountResource(false, 'Data Discount Gagal Dihapus!', null);
    }
}

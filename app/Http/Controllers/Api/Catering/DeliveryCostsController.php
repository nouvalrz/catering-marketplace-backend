<?php

namespace App\Http\Controllers\Api\Catering;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryCostsResource;
use App\Models\DeliveryCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class DeliveryCostsController extends Controller
{
    
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        
        //get products
        // $products = Product::with('category')->when(request()->q,
        $deliveryCosts = DeliveryCost::where('catering_id', $cateringId)->when(request()->q,
        function($deliveryCosts) {
            $deliveryCosts = $deliveryCosts->where('name', 'like', '%'. request()->q . '%');
        })->latest()->paginate(5);
        //return with Api Resource
        return new DeliveryCostsResource(true, 'List Data Discount', $deliveryCosts);
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
            'cost' => 'required',
            'min_distance' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //upload image
        // $image = $request->file('image');
        // $image->storeAs('public/products', $image->hashName());
        //create product
        $deliveryCosts = DeliveryCost::create([
            'catering_id' => $cateringId,
            'cost' => $request->cost,
            'min_distance' => $request->min_distance,

        ]);
        if($deliveryCosts) {
            //return success with Api Resource
            return new DeliveryCostsResource(true, 'Data Discounts Berhasil Disimpan!', $deliveryCosts);
        }
        //return failed with Api Resource
        return new DeliveryCostsResource(false, 'Data Discounts Gagal Disimpan!', null);
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
        
        $deliveryCosts = DeliveryCost::whereId($id)->first();
        if($deliveryCosts) {
            //return success with Api Resource
            return new DeliveryCostsResource(true, 'Detail Data Piscounts!', $deliveryCosts);
        }
        //return failed with Api Resource
        return new DeliveryCostsResource(false, 'Detail Data Discounts Tidak Ditemukan!', null);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliveryCost $deliveryCosts)
    {
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        
        $validator = Validator::make($request->all(), [
            'cost' => 'required,cost,'.$deliveryCosts->id,
            'min_distance' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $deliveryCosts->update([
            'catering_id' => $cateringId,
            'cost' => $request->cost,
            'min_distance' => $request->min_distance,

        ]);
        if($deliveryCosts) {
            //return success with Api Resource
            return new DeliveryCostsResource(true, 'Data Product Berhasil Diupdate!', $deliveryCosts);
        }
        //return failed with Api Resource
        return new DeliveryCostsResource(false, 'Data Product Gagal Diupdate!', null);
    }
    
    public function destroy(DeliveryCost $deliveryCosts)
    {
        //remove image
        // Storage::disk('local')->delete('public/products/'.basename($product->image));
        if($deliveryCosts->delete()) {
            //return success with Api Resource
            return new DeliveryCostsResource(true, 'Data Discount Berhasil Dihapus!', null);
        }
        //return failed with Api Resource
        return new DeliveryCostsResource(false, 'Data Discount Gagal Dihapus!', null);
    }
}

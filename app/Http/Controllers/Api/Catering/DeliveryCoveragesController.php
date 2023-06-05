<?php

namespace App\Http\Controllers\Api\Catering;

use App\Http\Controllers\Controller;
use App\Http\Resources\DeliveryCoveragesResource;
use App\Models\DeliveryCoverages;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class DeliveryCoveragesController extends Controller
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
        $deliveryCoverages = DeliveryCoverages::where('catering_id', $cateringId)->when(request()->q,
        function($deliveryCoverages) {
            $deliveryCoverages = $deliveryCoverages->where('name', 'like', '%'. request()->q . '%');
        })->latest()->paginate(5);
        //return with Api Resource
        return new DeliveryCoveragesResource(true, 'List Data Discount', $deliveryCoverages);
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
            'district_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //upload image
        // $image = $request->file('image');
        // $image->storeAs('public/products', $image->hashName());
        //create product
        $deliveryCoverages = DeliveryCoverages::create([
            'catering_id' => $cateringId,
            'district_id' => $request->district_id,

        ]);
        if($deliveryCoverages) {
            //return success with Api Resource
            return new DeliveryCoveragesResource(true, 'Data Discounts Berhasil Disimpan!', $deliveryCoverages);
        }
        //return failed with Api Resource
        return new DeliveryCoveragesResource(false, 'Data Discounts Gagal Disimpan!', null);
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
        
        $deliveryCoverages = DeliveryCoverages::whereId($id)->first();
        if($deliveryCoverages) {
            //return success with Api Resource
            return new DeliveryCoveragesResource(true, 'Detail Data Piscounts!', $deliveryCoverages);
        }
        //return failed with Api Resource
        return new DeliveryCoveragesResource(false, 'Detail Data Discounts Tidak Ditemukan!', null);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, DeliveryCoverages $deliveryCoverages)
    {
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        
        $validator = Validator::make($request->all(), [
            'district_id' => 'required,district_id,'.$deliveryCoverages->id,
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        
        $deliveryCoverages->update([
            'catering_id' => $cateringId,
            'district_id' => $request->district_id,

        ]);
        if($deliveryCoverages) {
            //return success with Api Resource
            return new DeliveryCoveragesResource(true, 'Data Product Berhasil Diupdate!', $deliveryCoverages);
        }
        //return failed with Api Resource
        return new DeliveryCoveragesResource(false, 'Data Product Gagal Diupdate!', null);
    }
    
    public function destroy(DeliveryCoverages $deliveryCoverages)
    {
        //remove image
        // Storage::disk('local')->delete('public/products/'.basename($product->image));
        if($deliveryCoverages->delete()) {
            //return success with Api Resource
            return new DeliveryCoveragesResource(true, 'Data Discount Berhasil Dihapus!', null);
        }
        //return failed with Api Resource
        return new DeliveryCoveragesResource(false, 'Data Discount Gagal Dihapus!', null);
    }
}

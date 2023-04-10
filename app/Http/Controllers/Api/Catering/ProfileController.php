<?php

namespace App\Http\Controllers\Api\Catering;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CateringResource;
use App\Http\Resources\ProductResource;
use App\Models\Catering;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

class ProfileController extends Controller
{
    public function show($id)
    {
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        
        $catering = Catering::whereId($cateringId)->first();
        if($catering) {
            //return success with Api Resource
            return new CateringResource(true, 'Detail Data Product!', $catering);
        }
        //return failed with Api Resource
        return new CateringResource(false, 'Detail Data Product Tidak Ditemukan!', null);
    }
    public function update(Request $request, Catering $catering)
    {
        $userId = auth()->guard('api_catering')->user()->id;
        $catering = Catering::find($userId);
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            // 'catering_id' => 'required',
            'email' => 'required',
            'description' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'zipcode' => 'required',
            'province_id' => 'required',
            'regency_id' => 'required',
            'district_id' => 'required',
            'village_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //check image update
        if ($request->file('image')) {
            //remove old image
            // Storage::disk('local')->delete('public/products/'.basename($product->image));
            //upload new image
            // $image = $request->file('image');
            // $image->storeAs('public/products', $image->hashName());
            //update product with new image
            $catering->update([
                // 'image' => $image->hashName(),
                'name' => $request->name,
                // 'slug' => Str::slug($request->title, '-'),
                // 'catering_id' => $cateringId,
                // 'user_id' => auth()->guard('api_admin')->user()->id,
                'description' => $request->description,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'zipcode' => $request->zipcode,
                'province_id' => $request->province_id,
                'regency_id' => $request->regency_id,
                'district_id' => $request->district_id,
                'village_id' => $request->village_id,

            ]);
        }
        //update product without image
        $catering->update([
            // 'image' => $image->hashName(),
            'name' => $request->name,
            // 'slug' => Str::slug($request->title, '-'),
            // 'catering_id' => $cateringId,
            // 'user_id' => auth()->guard('api_admin')->user()->id,
            'description' => $request->description,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'province_id' => $request->province_id,
            'regency_id' => $request->regency_id,
            'district_id' => $request->district_id,
            'village_id' => $request->village_id,
            'delivery_start_time' => $request->delivery_start_time,
            'delivery_end_time' => $request->delivery_end_time,

        ]);
        if($catering) {
            //return success with Api Resource
            return new CateringResource(true, 'Data Product Berhasil Diupdate!', $catering);
        }
        //return failed with Api Resource
        return new CateringResource(false, 'Data Product Gagal Diupdate!', null);
    }
}

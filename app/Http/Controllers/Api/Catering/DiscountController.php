<?php

namespace App\Http\Controllers\Api\Catering;

use App\Http\Controllers\Controller;
use App\Http\Resources\DiscountResource;
use App\Models\Discounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;


class DiscountController extends Controller
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
        $discounts = Discounts::where('catering_id', $cateringId)->when(request()->q,
        function($discounts) {
            $discounts = $discounts->where('name', 'like', '%'. request()->q . '%');
        })->latest()->paginate(5);
        //return with Api Resource
        return new DiscountResource(true, 'List Data Discount', $discounts);
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
        $discounts = Discounts::create([
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
        
        $discounts = Discounts::whereId($id)->first();
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
    public function update(Request $request, Discounts $discounts)
    {
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        
        $validator = Validator::make($request->all(), [
            'type' => 'required,type,'.$discounts->id,
            'title' => 'required',
            // 'catering_id' => 'required',
            'description' => 'required',
            'percentage' => 'required',
            'price' => 'required',
            'minimum_spend' => 'required',
            'maximum_disc' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
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
        if($discounts) {
            //return success with Api Resource
            return new DiscountResource(true, 'Data Product Berhasil Diupdate!', $discounts);
        }
        //return failed with Api Resource
        return new DiscountResource(false, 'Data Product Gagal Diupdate!', null);
    }
    
    public function destroy(Discounts $discounts)
    {
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

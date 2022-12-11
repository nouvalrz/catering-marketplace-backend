<?php

namespace App\Http\Controllers\Api\Catering;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Catering;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;


class ProductController extends Controller
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
        $products = Product::where('catering_id', $cateringId)->when(request()->q,
        function($products) {
            $products = $products->where('name', 'like', '%'. request()->q . '%');
        })->latest()->paginate(5);
        //return with Api Resource
        return new ProductResource(true, 'List Data Products', $products);
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
            'name' => 'required',
            // 'catering_id' => 'required',
            'description' => 'required',
            'weight' => 'required',
            'price' => 'required',
            'minimum_quantity' => 'required',
            'maximum_quantity' => 'required',
            'is_free_delivery' => 'required',
            'is_hidden' => 'required',
            'is_available' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //upload image
        // $image = $request->file('image');
        // $image->storeAs('public/products', $image->hashName());
        //create product
        $product = Product::create([
            // 'image' => $image->hashName(),
            'name' => $request->name,
            // 'slug' => Str::slug($request->title, '-'),
            'catering_id' => $cateringId,
            // 'user_id' => auth()->guard('api_admin')->user()->id,
            'description' => $request->description,
            'weight' => $request->weight,
            'price' => $request->price,
            'minimum_quantity' => $request->minimum_quantity,
            'maximum_quantity' => $request->maximum_quantity,
            'is_free_delivery' => $request->is_free_delivery,
            'is_hidden' => $request->is_hidden,
            'is_available' => $request->is_available,
            'image_id' => $cateringId,

        ]);
        if($product) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil Disimpan!', $product);
        }
        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Disimpan!', null);
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
        
        $product = Product::whereId($id)->first();
        if($product) {
            //return success with Api Resource
            return new ProductResource(true, 'Detail Data Product!', $product);
        }
        //return failed with Api Resource
        return new ProductResource(false, 'Detail Data Product Tidak Ditemukan!', null);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        
        $validator = Validator::make($request->all(), [
            'name' => 'required,name,'.$product->id,
            // 'catering_id' => 'required',
            'description' => 'required',
            'weight' => 'required',
            'price' => 'required',
            'minimum_quantity' => 'required',
            'maximum_quantity' => 'required',
            'is_free_delivery' => 'required',
            'is_hidden' => 'required',
            'is_available' => 'required',
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
            $product->update([
                // 'image' => $image->hashName(),
                'name' => $request->name,
                // 'slug' => Str::slug($request->title, '-'),
                'catering_id' => $cateringId,
                // 'user_id' => auth()->guard('api_admin')->user()->id,
                'description' => $request->description,
                'weight' => $request->weight,
                'price' => $request->price,
                'minimum_quantity' => $request->minimum_quantity,
                'maximum_quantity' => $request->maximum_quantity,
                'is_free_delivery' => $request->is_free_delivery,
                'is_hidden' => $request->is_hidden,
                'is_available' => $request->is_available,
                'image_id' => $cateringId,

            ]);
        }
        //update product without image
        $product->update([
            'name' => $request->name,
            // 'slug' => Str::slug($request->title, '-'),
            'catering_id' => $cateringId,
            'description' => $request->description,
            'weight' => $request->weight,
            'price' => $request->price,
            'minimum_quantity' => $request->minimum_quantity,
            'maximum_quantity' => $request->maximum_quantity,
            'is_free_delivery' => $request->is_free_delivery,
            'is_hidden' => $request->is_hidden,
            'is_available' => $request->is_available,
            'image_id' => $cateringId,

        ]);
        if($product) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil Diupdate!', $product);
        }
        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Diupdate!', null);
    }
    /**
     * Remove the specified resource from storage.
     *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(Product $product)
    {
        //remove image
        // Storage::disk('local')->delete('public/products/'.basename($product->image));
        if($product->delete()) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil Dihapus!', null);
        }
        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Dihapus!', null);
    }

}

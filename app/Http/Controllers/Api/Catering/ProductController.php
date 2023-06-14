<?php

namespace App\Http\Controllers\Api\Catering;

use App\Models\Product;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\ProductDetailResource;
use App\Http\Resources\ProductResource;
use App\Models\Categories;
use App\Models\Catering;
use App\Models\CategoriesProduct;
use App\Models\ProductOption;
use App\Models\ProductOptionDetail;
use Facade\FlareClient\Http\Response;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Validation\Rule;


class ProductController extends Controller
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
        // $productFilter = Product::where('catering_id', $cateringId)

        $product = Product::where('is_available', 'like', '%' . request()->available . '%' );

        // asli
        $products = $product->where('catering_id', $cateringId)->when(request()->q,
        function($products) {
            $products = $products->where('name', 'like', '%'. request()->q . '%');
        })->latest()->paginate(request()->pages);

        // if(request()->filter != ''){
        //     $products = Product::where('catering_id', $cateringId)->where('is_available', '=', request()->filter)->latest();
        // }
        // $products = $products->where('name', 'like', '%'. request()->q . '%')->latest()->paginate(20);
        // function($products) {
        //     $products = $products->when(request()->q,
        //     function($products){
        //         $products = $products->where('name', 'like', '%'. request()->q . '%')
        //         ->orWhere('is_available', 'like', '%'. request()->q . '%');
        //     });
        // })->latest()->paginate(20);
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

        // dd($request->category_id);

        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        $validator = Validator::make($request->all(), [
            // 'image' => 'required|image|mimes:jpeg,jpg,png|max:500|dimensions:ratio=1/1',
            'name' => 'required',
            // 'catering_id' => 'required',
            'description' => 'required',
            'weight' => 'required|numeric|min:0',
            'price' => 'required|numeric|min:0',
            'minimum_quantity' => 'required|numeric|min:0',
            'maximum_quantity' => 'required|numeric|min:0',
            'is_available' => 'required',
            'category_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());
        //create product
        $product = Product::create([
            'image' => $image->hashName(),
            'name' => $request->name,
            // 'slug' => Str::slug($request->title, '-'),
            'category_id' => $request->category_id,
            'catering_id' => $cateringId,
            'description' => $request->description,
            'weight' => $request->weight,
            'price' => $request->price,
            'minimum_quantity' => $request->minimum_quantity,
            'maximum_quantity' => $request->maximum_quantity,
            'is_available' => $request->is_available,

        ]);

        // $index = 0;
        $options = $request->options;
        // return new ProductResource(true, $options, $options);
        
        foreach($options as $option){
            // return new ProductResource(true, $options, $option);

            $optionData = ProductOption::create([
                // 'product_id' => 3,
                'product_id' => $product->id,
                'option_name' => $option['nameOption'],
                'option_type' => $option['optionType'],
                'minimum_selection' => $option['minSelect'],
                'maximum_selection' => $option['maxSelect'],
                'is_active' => $option['isActive'],

            ]);

            // $items = $option->flatten(1)->value()->all();
            $items = $option['items'];
            foreach($items as $item){
                $itemData = ProductOptionDetail::create([
                    'product_options_id' => $optionData->id,
                    'option_choice_name' => $item['nameItem'],
                    'additional_price' => $item['price'],
                    'is_available' => $item['isAvailable'],

                ]);
            };
            // $index+=1;
        };
        return new ProductResource(true, $options, $optionData);

        // dd($request->category_id);
        // Log::debug($request->category_id);
        // $category = Categories::find($request->category_id);
        // $product->categories()->attach($category);

        if($product) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil Disimpan!', $request->category_id);
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
        // $product = Product::whereId($id)->first();
        // $product = DB::table('categories_product')->where('product_id', '=', $id)->get('id');
        // $product = DB::table('categories_product')->where('product_id', $id);

        $product->link = asset('storage/products/');

        $options = ProductOption::with('optionDetail')->where('product_id', $id)->get();

        if($product) {
            //return success with Api Resource
            return new ProductDetailResource(true, 'Detail Data Product!', $product, $options);
        }
        //return failed with Api Resource
        return new ProductDetailResource(false, 'Detail Data Product Tidak Ditemukan!', null, null);
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
        // dd($product);
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        $idProd = $product->id;
        
        $validator = Validator::make($request->all(), [
            // 'image' =>[Rule::requiredIf(function (){
            //     if (Product::whereNotNull('image')->where('id', $idProd)->exists()) {
            //        return false;
            //     }
            //       return true;
            //    }),'image','mimes:jpeg,png,jpg','max:500'],
            // 'image' => 'image|mimes:jpeg,jpg,png|max:500|dimensions:ratio=1/1',
            'name' => 'required',
            'category_id' => 'required',
            // 'catering_id' => 'required',
            'description' => 'required',
            'weight' => 'required|numeric|min:1',
            'price' => 'required|numeric|min:1',
            'minimum_quantity' => 'required|numeric|min:0',
            'maximum_quantity' => 'required|numeric|min:1',
            'is_available' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //check image update
        if ($request->file('image')) {
            //remove old image
            Storage::disk('local')->delete('public/products/'.basename($product->image));
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());
            //update product with new image
            $product->update([
                'image' => $image->hashName(),
                'name' => $request->name,
                // 'slug' => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'catering_id' => $cateringId,
                // 'user_id' => auth()->guard('api_admin')->user()->id,
                'description' => $request->description,
                'weight' => $request->weight,
                'price' => $request->price,
                'minimum_quantity' => $request->minimum_quantity,
                'maximum_quantity' => $request->maximum_quantity,
                'is_available' => $request->is_available,
                'image_id' => $cateringId,

            ]);
        }else{
            //update product without image
            $product->update([
                'name' => $request->name,
                // 'slug' => Str::slug($request->title, '-'),
                'category_id' => $request->category_id,
                'catering_id' => $cateringId,
                'description' => $request->description,
                'weight' => $request->weight,
                'price' => $request->price,
                'minimum_quantity' => $request->minimum_quantity,
                'maximum_quantity' => $request->maximum_quantity,
                'is_available' => $request->is_available,
                // 'image_id' => $cateringId,
    
            ]);
        }
        if($product) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil Diupdate!', $product);
        }
        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Diupdate!', null);
    }

    public function updateOptionItem(Request $request){
        
        if($request->type == 'item'){
            if($request->idItem){
                $items = ProductOptionDetail::whereId($request->idItem)->first();
                $items->update([
                    'option_choice_name' => $request->option_choice_name,
                    'additional_price' => $request->additional_price,
                    'is_available' => $request->is_available,
                ]);
                if($items){
                    return new ProductResource(true, 'Data Items Berhasil Diupdate!', $items);
                }
                return new ProductResource(false, 'Data Items Gagal Diupdate!', null);
            }
            else{

                $items = ProductOptionDetail::create([
                    'product_options_id' => $request->product_options_id,
                    'option_choice_name' => $request->option_choice_name,
                    'additional_price' => $request->additional_price,
                    'is_available' => $request->is_available,
                ]);
    
                if($items){
                    return new ProductResource(true, 'Data Items Berhasil ditambah!', $items);
                }
                return new ProductResource(false, 'Data Items asd !', null);

            }

            return new ProductResource(false, 'Data Items salah !', null);

        }
        
        if($request->type == 'option'){
            if($request->idOption){
                $options = ProductOption::whereId($request->idOption)->first();
                $options->update([
                    'option_name' => $request->option_name,
                    'option_type' => $request->option_type,
                    'minimum_selection' => $request->minimum_selection,
                    'maximum_selection' => $request->maximum_selection,
                    'is_active' => $request->is_active,
                ]);
                if($options){
                    return new ProductResource(true, 'Data Option Berhasil Diupdate!', $options);
                }
                return new ProductResource(false, 'Data Option Gagal Diupdate!', null);
            }else{
                
                $options = ProductOption::create([
                    'product_id' => $request->product_id,
                    'option_name' => $request->option_name,
                    'option_type' => $request->option_type,
                    'minimum_selection' => $request->minimum_selection,
                    'maximum_selection' => $request->maximum_selection,
                    'is_active' => $request->is_active,
                ]);

                $items = $request->items;
                foreach($items as $item){
                    $itemData = ProductOptionDetail::create([
                        'product_options_id' => $options->id,
                        'option_choice_name' => $item['nameItem'],
                        'additional_price' => $item['price'],
                        'is_available' => $item['isAvailable'],

                    ]);
                };
    
                if($options){
                    return new ProductResource(true, 'Data Option Berhasil ditambah!', $options);
                }
                return new ProductResource(false, 'Data Option asd !', null);

            }
        }
        return $request;
    }

    public function deleteOption($id){
        $option = ProductOption::whereId($id)->first();

        $items = ProductOptionDetail::where('product_options_id', $id)->get('id');
        if($items){
            foreach($items as $item){
                ProductOptionDetail::whereId($item->id)->first()->delete();
            }
        }

        if($option){
            $option->delete();
            // if($option->delete()) {
                // return success with Api Resource
                return new ProductResource(true, 'Data Option Berhasil Dihapus!',  $item);
            // }
        }
        //return failed with Api Resource
        return new ProductResource(false, 'Data Option Gagal Dihapus!', $item);
    
    }

    public function deleteItem($id){
        $item = ProductOptionDetail::whereId($id)->first();
        $item->delete();
        if($item->delete()) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Item Berhasil Dihapus!', null);
        }
        //return failed with Api Resource
        return new ProductResource(false, 'Data item Gagal Dihapus!', $item);
    
    }

    public function changeAvailableProduct(Request $request, $id)
    {
        // dd($request->status);
        $product      = Product::findOrFail($id);
        $product->is_available = $request->is_available;
        $product->save();

        if($product) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil!', $product);
        }
        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Diupdate!', $product);
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
        Storage::disk('local')->delete('public/products/'.basename($product->image));
            // Storage::disk('local')->delete('public/products/'.basename($product->image));
        if($product->delete()) {
            //return success with Api Resource
            return new ProductResource(true, 'Data Product Berhasil Dihapus!', null);
        }
        //return failed with Api Resource
        return new ProductResource(false, 'Data Product Gagal Dihapus!', null);
    }

}

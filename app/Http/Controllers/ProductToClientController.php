<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductToClientController extends Controller
{
    //
    public function getProductDetail(Request $request){
        request()->validate([
            "product_id" => "required"
        ]);

        $product = DB::table('products')->select(['products.id','products.name', 'products.price', 'products.description', 'products.weight', 'products.minimum_quantity', 'products.maximum_quantity', 'products.is_free_delivery', 'products.is_hidden', 'products.is_available', 'images.original_path'])->join('images', 'images.id', '=', 'products.image_id')->where([['products.id', request('product_id')], ['products.is_hidden', 0]])->get()->first();

        return response()->json($product);
    }
}

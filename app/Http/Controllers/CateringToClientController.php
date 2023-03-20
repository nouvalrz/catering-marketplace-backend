<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CateringToClientController extends Controller
{
    //
    public function getRelevantCatering(Request $request){
        request()->validate([
            'district_name' => 'required'
        ]);

//        $caterings = DB::table('caterings')->select(['caterings.id'])->join('villages', 'caterings.village_id', '=', 'villages.id')->join('districts', 'villages.district_id', '=', 'districts.id')->where('districts.name', 'like', '%' . request('district_name') . '%')->limit(15)->get();
//
//        $complete_caterings = array();
//
//        foreach ($caterings as $catering){
//            $c = array();
//            $c['catering'] = DB::table('caterings')->select(['caterings.id','caterings.name AS name','villages.name AS village_name','images.original_path', 'caterings.latitude', 'caterings.longitude', 'caterings.total_sales'])->join('villages', 'caterings.village_id', '=', 'villages.id')->join('images', 'caterings.image_id', '=', 'images.id')->where('caterings.id', $catering->id)->get()->first();
//
//
//            $c['products'] = DB::table('products')->select(['products.id', 'products.name', 'products.price', 'images.original_path'])->join('caterings', 'caterings.id', '=', 'products.catering_id')->join('images', 'images.id', '=', 'products.image_id')->where('products.catering_id', $catering->id)->limit(2)->get();
//
//            $complete_caterings[] = $c;
//            $finish_caterings = array('caterings'=>$complete_caterings);
//        }

        $finish_caterings = array();

        $finish_caterings["caterings"] = Catering::with("recommendation_products")->with( "village.district")->whereRelation("village.district", "name", "like", "%" . request('district_name') . "%")->limit(15)->get()->map(function ($catering){
            $catering->setRelation('recommendation_products', $catering->recommendation_products->take(2));
            return $catering;
        });

        return response()->json($finish_caterings);
//        return json_encode($finish_caterings);

    }

    public function getProductsFromCatering($id){
//        $p = DB::table('products')->select(['products.id','products.name', 'products.price', 'products.description', 'products.weight', 'products.minimum_quantity', 'products.maximum_quantity', 'products.is_free_delivery', 'products.is_hidden', 'products.is_available', 'images.original_path', 'products.is_customable'])->join('images', 'images.id', '=', 'products.image_id')->where([['products.catering_id', $id], ['products.is_hidden', 0]])->get();
//
        $products = array();
        $products['products'] = Product::with("product_options", "product_options.product_option_details")->where([['catering_id', $id], ['is_hidden', 0]])->get()->toArray();
//        Product::with("product_options", "product_options.product_option_details")->get()->toJson(JSON_PRETTY_PRINT);


        return response()->json($products);

    }
}

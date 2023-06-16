<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\Discount;
use App\Models\Product;
use App\Models\Reviews;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CateringToClientController extends Controller
{
    //
    public function getRelevantCatering(Request $request)
    {
        request()->validate([
            'district_name' => 'required',
            'customer_latitude' => 'required',
            'customer_longitude' => 'required'
        ]);

        $customerLatitude = request('customer_latitude');
        $customerLongitude = request('customer_longitude');

        $finish_caterings = array();
//
        $finish_caterings["caterings"] = Catering::where('isVerified', 'yes')->with(["recommendation_products", "village.district", "categories", "discounts"])->withCount('discounts')->orderByDesc("total_sales")->orderByDesc('rate')->whitDistance([$customerLongitude, $customerLatitude])->whereDistance([$customerLongitude, $customerLatitude],10000)->limit(15)->get()->map(function ($catering) {
            $catering->setRelation('recommendation_products', $catering->recommendation_products->take(2));
            return $catering;
        });

        $now = Carbon::now();
        $date = Carbon::parse($now)->toDateString();
        $discountAdmin = Discount::where('catering_id', 0)->whereDate('start_date', '>=', $date )->whereDate('end_date', '>=', $date )->get();

        $finish_caterings['admin_discounts'] = $discountAdmin;


////        $finish_caterings["caterings"] = Catering::select('name')->whitDistance([115.214485, -8.711786])->whereDistance([115.214485, -8.711786],10000)->get();
//
//        $finish_caterings["caterings"] = Catering::with(["recommendation_products", "village.district", "categories", "discounts"])->withCount('discounts')->whereRelation("village.district", "name", "like", "%" . request('district_name') . "%")->limit(15)->orderByDesc("total_sales")->orderByDesc('rate')->get()->map(function ($catering) {
//            $catering->setRelation('recommendation_products', $catering->recommendation_products->take(2));
//            return $catering;
//        });

        return response()->json($finish_caterings);
//        return json_encode($finish_caterings);
    }

    public function getSearchResultCatering(Request $request)
    {

        request()->validate([
            'keyword' => 'required',
            'district_name' => 'required',
            'customer_latitude' => 'required',
            'customer_longitude' => 'required'
        ]);

        $customerLatitude = request('customer_latitude');
        $customerLongitude = request('customer_longitude');

        $finish_caterings = array();

        $finish_caterings["caterings"] = Catering::where('isVerified', 'yes')->with(["recommendation_products" => function ($query) {
            $query->where("name", "like", "%" . request('keyword') . "%");
        }, "village.district", "categories", "discounts"])->withCount('discounts')->whereRelation("recommendation_products", "name", "like", "%" . request('keyword') . "%")->whitDistance([$customerLongitude, $customerLatitude])->whereDistance([$customerLongitude, $customerLatitude],10000)->limit(15)->get()->map(function ($catering) {
            $catering->setRelation('recommendation_products', $catering->recommendation_products->take(2));
            return $catering;
        });

        $now = Carbon::now();
        $date = Carbon::parse($now)->toDateString();
        $discountAdmin = Discount::where('catering_id', 0)->whereDate('start_date', '>=', $date )->whereDate('end_date', '>=', $date )->get();

        $finish_caterings['admin_discounts'] = $discountAdmin;

//        $finish_caterings["caterings"] = Catering::with(["recommendation_products" => function ($query) {
//            $query->where("name", "like", "%" . request('keyword') . "%");
//        }, "village.district", "categories", "discounts"])->withCount('discounts')->limit(15)->get()->map(function ($catering) {
//            $catering->setRelation('recommendation_products', $catering->recommendation_products->take(2));
//            return $catering;
//        });

        return response()->json($finish_caterings);
    }

    public function getCategoryResultCatering(Request $request){
        request()->validate([
            'keyword' => 'required',
            'district_name' => 'required',
            'customer_latitude' => 'required',
            'customer_longitude' => 'required'
        ]);

        $finish_caterings = array();
        $customerLatitude = request('customer_latitude');
        $customerLongitude = request('customer_longitude');


        $finish_caterings["caterings"] = Catering::where('isVerified', 'yes')->with(["recommendation_products","village.district", "categories", "discounts"])->withCount('discounts')->whereRelation("categories", "name", "like", "%" . request('keyword') . "%")->whitDistance([$customerLongitude, $customerLatitude])->whereDistance([$customerLongitude, $customerLatitude],10000)->limit(15)->get()->map(function ($catering) {
            $catering->setRelation('recommendation_products', $catering->recommendation_products->take(2));
            return $catering;
        });

        $now = Carbon::now();
        $date = Carbon::parse($now)->toDateString();
        $discountAdmin = Discount::where('catering_id', 0)->whereDate('start_date', '>=', $date )->whereDate('end_date', '>=', $date )->get();

        $finish_caterings['admin_discounts'] = $discountAdmin;


        return response()->json($finish_caterings);
    }

    public function getProductsFromCatering($id)
    {
//        $p = DB::table('products')->select(['products.id','products.name', 'products.price', 'products.description', 'products.weight', 'products.minimum_quantity', 'products.maximum_quantity', 'products.is_free_delivery', 'products.is_hidden', 'products.is_available', 'images.original_path', 'products.is_customable'])->join('images', 'images.id', '=', 'products.image_id')->where([['products.catering_id', $id], ['products.is_hidden', 0]])->get();
//
        $products = array();
        $products['products'] = Product::with("product_options", "product_options.product_option_details", )->where([['catering_id', $id], ['is_available', 1]])->get()->toArray();
//        Product::with("product_options", "product_options.product_option_details")->get()->toJson(JSON_PRETTY_PRINT);


        return response()->json($products);

    }

    public function getCateringDeliveryTimeRange($id)
    {
        $catering = Catering::find($id);

        return response()->json([
            "delivery_start_time" => $catering->delivery_start_time,
            "delivery_end_time" => $catering->delivery_end_time,
        ]);
    }

    public function getCateringReviews($id){
        $rateCountGroup = Reviews::where('catering_id', $id)->groupBy('star')->select('star', DB::raw('count(*) as total'))->get();


        $reviews = Reviews::with('customer.user:id,name')->where('catering_id', $id)->orderBy('created_at', 'desc')->get();
        $catering = Catering::find($id);

        return response()->json([ "catering_review" => ["rate" => $catering->rate ,"reviews" => $reviews, "rate_count" => $rateCountGroup]]);
    }

    public function getCateringDiscounts($id){
        $discounts = Discounts::where('catering_id', $id)->active()->get();
        return response()->json(["discounts" => $discounts]);
    }


    public function getCateringWorkDay($id){
        $workdays =  json_decode(Catering::find($id)->workday);
        $workday = array();
        foreach ($workdays as $workdaye){
            $workday[] = strtolower($workdaye->name);
        }
        return response()->json(["workday" => $workday]);
    }
}

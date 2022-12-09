<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CateringToClientController extends Controller
{
    //
    public function getRelevantCatering(Request $request){
        request()->validate([
            'district_name' => 'required'
        ]);

        $caterings = DB::table('caterings')->select(['caterings.id'])->join('villages', 'caterings.village_id', '=', 'villages.id')->join('districts', 'villages.district_id', '=', 'districts.id')->where('districts.name', 'like', '%' . request('district_name') . '%')->limit(15)->get();

        $complete_caterings = array();

        foreach ($caterings as $catering){
            $c = array();
            $c['catering'] = DB::table('caterings')->select(['caterings.id','caterings.name AS name','villages.name AS village_name','images.original_path'])->join('villages', 'caterings.village_id', '=', 'villages.id')->join('images', 'caterings.image_id', '=', 'images.id')->where('caterings.id', $catering->id)->get()->first();


            $c['products'] = DB::table('products')->select(['products.id', 'products.name', 'products.price', 'images.original_path'])->join('caterings', 'caterings.id', '=', 'products.catering_id')->join('images', 'images.id', '=', 'products.image_id')->where('products.catering_id', $catering->id)->limit(2)->get();

            $complete_caterings[] = $c;
            $finish_caterings = array('caterings'=>$complete_caterings);
        }
        return response()->json($finish_caterings);
//        return json_encode($finish_caterings);

    }
}

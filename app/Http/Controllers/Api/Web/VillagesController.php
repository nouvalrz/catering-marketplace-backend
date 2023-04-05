<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\VillagesResource;
use App\Models\Village;
use Illuminate\Http\Request;

class VillagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get village
        $village = Village::get();
        //return with Api Resource
        return new VillagesResource(true, 'List Data Village', $village);
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $villages = Village::where('district_id', '=', $id)->get();
        
        if($villages) {
            //return success with Api Resource
            return new VillagesResource(true, 'Detail Data Villages!', $villages);
        }
        //return failed with Api Resource
        return new VillagesResource(false, 'Detail Data Villages Tidak Ditemukan!', null);
    }

    /**
    * Display the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    // public function show($slug)
    // {
    //     $village = Village::with('products.village')
    //     //get count review and average review
    //     ->with('products', function ($query) {
    //     $query->withCount('reviews');
    //     $query->withAvg('reviews', 'rating');
    //     })
    //     ->where('slug', $slug)->first();
    //     if($village) {
    //         //return success with Api Resource
    //         return new VillagesResource(true, 'Data Product By Village : '.$village->name.'', $village);
    //     }
    //     //return failed with Api Resource
    //     return new VillagesResource(false, 'Detail Data Village Tidak Ditemukan!', null);
    // }
}

<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\RegencyResource;
use App\Models\Catering;
use App\Models\Regency;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class RegencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $echo $akun;
        // $userId = auth()->guard('api_catering')->user()->id;
        // $catering = Catering::find($akun);
        //get Regency
        $regency = Regency::get();
        //return with Api Resource
        return new RegencyResource(true, 'List Data Regency', $regency);
    }

    
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $regency = Regency::where('province_id', '=', $id)->get();
        
        if($regency) {
            //return success with Api Resource
            return new RegencyResource(true, 'Detail Data Regency!', $regency);
        }
        //return failed with Api Resource
        return new RegencyResource(false, 'Detail Data Regency Tidak Ditemukan!', null);
    }

    /**
    * Display the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    // public function show($slug)
    // {
    //     $regency = Regency::with('products.category')
    //     //get count review and average review
    //     ->with('products', function ($query) {
    //     $query->withCount('reviews');
    //     $query->withAvg('reviews', 'rating');
    //     })
    //     ->where('slug', $slug)->first();
    //     if($regency) {
    //         //return success with Api Resource
    //         return new RegencyResource(true, 'Data Regency By Category : '.$regency->name.'', $regency);
    //     }
    //     //return failed with Api Resource
    //     return new RegencyResource(false, 'Detail Data Category Tidak Ditemukan!', null);
    // }
}

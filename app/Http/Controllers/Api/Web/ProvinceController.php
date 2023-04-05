<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProvinceResource;
use App\Models\Province;
use Illuminate\Http\Request;

class ProvinceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get categories
        $province = Province::get();
        //return with Api Resource
        return new ProvinceResource(true, 'List Data Provice', $province);
    }

    /**
    * Display the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    // public function show($slug)
    // {
    //     $province = Province::with('products.category')
    //     //get count review and average review
    //     ->with('products', function ($query) {
    //     $query->withCount('reviews');
    //     $query->withAvg('reviews', 'rating');
    //     })
    //     ->where('slug', $slug)->first();
    //     if($province) {
    //         //return success with Api Resource
    //         return new ProvinceResource(true, 'Data Product By Category : '.$province->name.'', $province);
    //     }
    //     //return failed with Api Resource
    //     return new ProvinceResource(false, 'Detail Data Category Tidak Ditemukan!', null);
    // }
}

<?php

namespace App\Http\Controllers\Api\Web;

use App\Http\Controllers\Controller;
use App\Http\Resources\DistrictResource;
use App\Models\District;
use Illuminate\Http\Request;

class DistrictController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get Disctrict
        $disctrict = District::get();
        //return with Api Resource
        return new DistrictResource(true, 'List Data Disctrict', $disctrict);
    }
    
    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $district = District::where('regency_id', '=', $id)->get();
        
        if($district) {
            //return success with Api Resource
            return new DistrictResource(true, 'Detail Data District!', $district);
        }
        //return failed with Api Resource
        return new DistrictResource(false, 'Detail Data District Tidak Ditemukan!', null);
    }

    /**
    * Display the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    // public function show($slug)
    // {
    //     $disctrict = District::with('products.category')
    //     //get count review and average review
    //     ->with('products', function ($query) {
    //     $query->withCount('reviews');
    //     $query->withAvg('reviews', 'rating');
    //     })
    //     ->where('slug', $slug)->first();
    //     if($disctrict) {
    //         //return success with Api Resource
    //         return new DistrictResource(true, 'Data Product By Category : '.$disctrict->name.'', $disctrict);
    //     }
    //     //return failed with Api Resource
    //     return new DistrictResource(false, 'Detail Data Category Tidak Ditemukan!', null);
    // }
}

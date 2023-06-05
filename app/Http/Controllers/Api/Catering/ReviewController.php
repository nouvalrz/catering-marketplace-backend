<?php

namespace App\Http\Controllers\Api\Catering;

use App\Http\Controllers\Controller;
use App\Http\Resources\ReviewResource;
use App\Models\Reviews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ReviewController extends Controller
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
        $reviews = Reviews::with(['order', 'customer:id,name'])
            ->where('star', 'like', '%'. request()->rating . '%')
            ->when(request()->q,
                function($reviews) {
                    $reviews = $reviews->whereHas('customer', 
                        function($reviews) {
                            $reviews = $reviews->where('name', 'like', '%'. request()->q . '%');
                        })->orWhereHas('order',
                        function($reviews) {
                            $reviews = $reviews->where('invoice_number', 'like', '%'. request()->q . '%')
                                ->where('star', 'like', '%'. request()->rating . '%');
                        });
                })
            ->latest()->paginate(request()->pages);
        //return with Api Resource
        return new ReviewResource(true, 'List Data Review', $reviews);
    }

    public function show($id)
    {
        // $userId = auth()->guard('api_catering')->user()->id;
        // $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        
        $reviews = Reviews::with('order')->with('customer:id,name')->whereId($id)->first();
        if($reviews) {
            //return success with Api Resource
            return new ReviewResource(true, 'Detail Data Review!', $reviews);
        }
        //return failed with Api Resource
        return new ReviewResource(false, 'Detail Data Reviews Tidak Ditemukan!', null);
    }
}

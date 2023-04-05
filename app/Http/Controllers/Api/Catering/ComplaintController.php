<?php

namespace App\Http\Controllers\Api\Catering;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComplaintResource;
use App\Http\Resources\ComplaintsResource;
use App\Models\Complaints;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ComplaintController extends Controller
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
        $complaints = Complaints::when(request()->q,
        function($complaints) {
            $complaints = $complaints->where('status', 'like', '%'. request()->q . '%');
        })->latest()->paginate(5);
        //return with Api Resource
        return new ComplaintResource(true, 'List Data Complaints', $complaints);
    }
}

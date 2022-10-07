<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function profile(Request $request){
        $user = $request->user();
        $biodata = $user->biodata()->get()->first();
        return response()->json([
            "name"=> $user->name,
            "email"=> $user->email,
            "phone" => $biodata->phone,
            "gender" => $biodata->gender,
            "balance" => $biodata->balance,
            "points" => $biodata->points
        ]);
    }
}

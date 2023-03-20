<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    //
    public function profile(Request $request){
        $user = auth()->user();
        $biodata = $user->biodata()->get()->first();
        return response()->json([
            "name"=> $user->name,
            "email"=> $user->email,
            "phone" => $biodata->phone,
            "gender" => $biodata->gender,
            "balance" => $biodata->balance,
            "points" => $biodata->points,
        ]);
    }

    public function update(Request $request){
        $user = $request->user();
        $biodata = $user->biodata()->get()->first();

        request()->validate([
            'name' => 'required',
            'phone' => ['integer', "unique:customers,phone,{$biodata->id}"],
            'gender' => ['required']
        ]);

        $user->name = $request->name;
        $user->save();

        $biodata->phone = $request->phone;
        $biodata->gender = $request->gender;
        $biodata->save();

        return response("success", 200);
    }
}

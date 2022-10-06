<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CustomerAuthController extends Controller
{
    //
    public function login(Request $request){
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        if(!$token = auth()->attempt($request->only('email', 'password'))){
            return response(null, 401);
        }

        return response()->json([
            'token' => $token
        ]);
    }

    public function logout(Request $request){
        auth()->logout();
        return "logout";
    }
}

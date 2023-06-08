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
            'password' => 'required',
        ]);

        if(!$token = auth()->attempt($request->only('email', 'password'))){
            return response(null, 401);
        }

        $user = auth()->user();

        $user->fcm_token = request('fcm_token') ?? null;
        $user->save();

        return response()->json([
            'token' => $token,
            'type' => $user->type
        ]);
    }

    public function logout(Request $request){
        $user = auth()->user();
        $user->fcm_token = null;
        $user->save();
        auth()->logout();
        response()->json(["status" => "success"]);
    }
}

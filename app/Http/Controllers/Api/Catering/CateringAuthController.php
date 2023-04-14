<?php

namespace App\Http\Controllers\Api\Catering;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Models\Catering;

class CateringAuthController extends Controller
{
    public function index(Request $request)
    {
        //set validasi
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        //response error validasi
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }
        //get "email" dan "password" dari input
        $credentials = $request->only('email', 'password');

        //check jika "email" dan "password" tidak sesuai
        if(!$token = auth()->guard('api_catering')->attempt($credentials)){
        //response login "failed"
        return response()->json([
            'success' => false,
            'message' => 'Email or Password is incorrect'
            ], 401);
   
        }

        $valid = auth()->guard('api_catering')->user()->email_verified_at;

        // $akun = auth()->guard('api_catering')->user()->id;
        // redirect()->action('RegencyController@index', ['akun' => $akun]);

        if($valid){

            //response login "success" dengan generate "Token"
            return response()->json([
                'success' => true,
                'user' => auth()->guard('api_catering')->user(),
                'token' => $token,
                'valid' => $valid
                ], 200);
        }

        
        $otp = (new \Ichtrojan\Otp\Otp)->generate(request('email'), 6, 10);
        $otp_token = $otp->token;

        Mail::raw("Your OTP is $otp_token", function ($m) {
            $m->to(request('email'))->subject('Email Verification OTP');
        });
        //response login "success" dengan generate "Token"
        return response()->json([
            'success' => true,
            'user' => auth()->guard('api_catering')->user(),
            // 'token' => $token,
            'valid' => $valid,
            'message' => 'belum'
            ], 200);

    }

    public function getOtp(){
        
        $otp = (new \Ichtrojan\Otp\Otp)->generate(request('email'), 6, 10);
        $otp_token = $otp->token;

        Mail::raw("Your OTP is $otp_token", function ($m) {
            $m->to(request('email'))->subject('Email Verification OTP');
        });

        return response()->json([
            'success' => true,
            'message' => 'Kode berhasil dikirim'
        ], 200);
    }


    public function getUser()
    {
        $user = auth()->guard('api_catering')->user();

        $id = $user->id;

        $img = Catering::whereId($id)->first()->image;

        $user->image = $img;



        //response data "user" yang sedang login
        return response()->json([
            'success' => true,
            'user' => $user
        ], 200);
    }
    public function refreshToken(Request $request)
    {
        //refresh "token"
        $refreshToken = JWTAuth::refresh(JWTAuth::getToken());
        //set user dengan "token" baru
        $user = JWTAuth::setToken($refreshToken)->toUser();
        //set header "Authorization" dengan type Bearer + "token" baru
        $request->headers->set('Authorization','Bearer '.$refreshToken);
        //response data "user" dengan "token" baru
        return response()->json([
            'success' => true,
            'user' => $user,
            'token' => $refreshToken,
        ], 200);
    }

    public function logout()
    {
        //remove "token" JWT
        $removeToken = JWTAuth::invalidate(JWTAuth::getToken());
        //response "success" logout
        return response()->json([
            'success' => true,
        ], 200);
    }

}

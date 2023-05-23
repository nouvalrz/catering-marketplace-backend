<?php

namespace App\Http\Controllers\Api\Catering;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Mail;
use App\Models\Catering;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;

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
        // $credentials = $request->only('email', 'password');
        $credentials = ['email' => $request->email, 'password' => $request->password, 'type' => 'catering'];

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

        Mail::raw("Mohon jaga kerahasiaan kode ini 
            \ndan jangan berikan kode ini kepada siapapun 
            \n\nKode OTP anda adalah $otp_token
            \n\n- CateringKu -", function ($m) {
            $m->to(request('email'))->subject('CateringKu Email Verification OTP');
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

        Mail::raw("Mohon jaga kerahasiaan kode ini 
            \ndan jangan berikan kode ini kepada siapapun 
            \n\nKode OTP anda adalah $otp_token
            \n\n- CateringKu -", function ($m) {
            $m->to(request('email'))->subject('CateringKu Email Verification OTP');
        });

        return response()->json([
            'success' => true,
            'message' => 'Kode berhasil dikirim'
        ], 200);
    }

    public function checkEmail(Request $request){
        //set validasi
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);
        //response error validasi
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        $user = User::where('email', '=', $request->email)->first();

        if(!$user){
            return response()->json([
                'success' => false,
                'message' => 'Email is incorrect'
                ], 401);
        }else{

            $otp = (new \Ichtrojan\Otp\Otp)->generate(request('email'), 6, 10);
            $otp_token = $otp->token;
    
            Mail::raw("Mohon jaga kerahasiaan kode ini 
                \ndan jangan berikan kode ini kepada siapapun 
                \n\nKode OTP anda adalah $otp_token
                \n\n- CateringKu -", function ($m) {
                $m->to(request('email'))->subject('CateringKu Email Verification OTP');
            });
            //response login "success" dengan generate "Token"
            return response()->json([
                'success' => true,
                // 'user' => auth()->guard('api_catering')->user(),
                // 'token' => $token,
                // 'valid' => $user,
                'message' => 'OTP berhasil dikirim',
                // 'ss' => $request->email,
                ], 200);
        };




        // $user->update([
        //     'password' => Hash::make($request->password),
        // ]);
    }

    public function checkOtp(Request $request){
        request()->validate([
            'email' => 'required',
            'otp' => 'required'
            ]);

            //response error validasi
            // if($validator->fails()){
            //     return response()->json($validator->errors(), 422);
            // }

        
        if($request->newEmail){
            $otp = (new \Ichtrojan\Otp\Otp)->validate(request('newEmail'), request('otp'));

        }else{
            $otp = (new \Ichtrojan\Otp\Otp)->validate(request('email'), request('otp'));
        }
        
        
        if($otp->status){
            $user = User::where('email', request('email'))->first();
            $catering = Catering::where('email', request('email'))->first();
            // $user->email_verified_at = Carbon::now()->timestamp;
            // $user->save();

            if($request->newEmail){
                $user->email = $request->newEmail;
                $user->save();
                $catering->email = $request->newEmail;
                $catering->save();

                
                return response()->json([
                    'success' => true,
                    'status' => $otp->status,
                    'token' => 'hehe',
                    'message' => 'OTP Validation is success'
                ]);
                }

            return response()->json([
                'success' => true,
                'status' => $otp->status,
                // 'token' => $token,
                'message' => 'OTP Validation is success'
            ]);
        }else{
            return response()->json([
            'success' => false,
            'message' => 'OTP is not valid'
            ], 401);
        }

    }
    public function changePassword(Request $request){
        
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required|confirmed',
            'password_confirmation' => ['required', 'same:password']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        
        $user = User::where('email', request('email'))->first();

        if(!$user){
            
        return response()->json([
            'success' => false,
            'message' => 'Change password gagal'
        ], 401);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // return response()->json([
        //     'success' => true,
        //     'message' => 'Change password berhasil'
        // ], 401);


    }

    public function checkUser(Request $request)
    {
        //set validasi
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);
        //response error validasi
        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        };
        $pass = $request->password;
        $user = User::where('email', $request->email)->first();

        if(!$user){
            //response data "user" yang sedang login
            return response()->json([
                'success' => false,
                'message' => 'user tidak ditemukan'
            ], 401);
        }

        if(Hash::check($pass, $user->password)){
            //response data "user" yang sedang login
            return response()->json([
                'success' => true,
                'message' => 'cocok'
            ], 200);
        };

        return response()->json([
            'success' => false,
            'message' => 'password salah'
        ], 401);
    }

    public function changeEmailCheck(Request $request){
        
        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'emailNew' => 'required|email|unique:users,email',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        
        $user = User::where('email', request('email'))->first();

        if(!$user){
            
        return response()->json([
            'success' => false,
            'message' => 'user tidak ditemukan'
        ], 401);
        }

        $otp = (new \Ichtrojan\Otp\Otp)->generate(request('emailNew'), 6, 10);
        $otp_token = $otp->token;

        Mail::raw("Mohon jaga kerahasiaan kode ini 
            \ndan jangan berikan kode ini kepada siapapun 
            \n\nKode OTP anda adalah $otp_token
            \n\n- CateringKu -", function ($m) {
            $m->to(request('email'))->subject('CateringKu Email Verification OTP');
        });

        return response()->json([
            'success' => true,
            'message' => 'Kode berhasil dikirim'
        ], 200);

        // $user->email = $request->emailNew;
        // $user->save();

    }
    public function getUser()
    {
        $user = auth()->guard('api_catering')->user();

        $id = $user->id;

        // $img = Catering::whereId($id)->first()->image;
        // if($img){

            // $user->image = $img;
        // }



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

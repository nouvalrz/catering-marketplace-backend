<?php

namespace App\Http\Controllers\Api\Catering;

use App\Http\Controllers\Controller;
use App\Http\Resources\CateringResource;
use App\Models\Catering;
use App\Models\DeliveryCost;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;


class UserController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|confirmed',
            'password_confirmation' => ['required', 'same:password']
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        //create customer
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            // 'password' => Hash::make($request->password),
            'type' => 'catering'
        ]);

        // Catering::create([
        //     'user_id' => $user->id,
        //     'name' => $request->name,
        //     'email' => $request->email,
        //     'phone' => $request->phone,
        //     'address' => $request->address,
        //     'zipcode' => $request->zipcode,
        //     // 'province_id' => 0,
        //     // 'regency_id' => 0,
        //     // 'district_id' => 0,
        //     // 'village_id' => 0,
        //     'isVerified' => 'no',

        // ]);

        // DeliveryCost::create([
        //     'catering_id' => $user->id,
        //     'cost' => 0,
        //     'min_distance' => 0
        // ]);

        

        
        $otp = (new \Ichtrojan\Otp\Otp)->generate(request('email'), 6, 10);
        $otp_token = $otp->token;

        Mail::raw("Your OTP is $otp_token", function ($m) {
            $m->to(request('email'))->subject('Email Verification OTP');
        });

        return $otp;

        // if($user) {
        //     //return with Api Resource
        //     return new CateringResource(true, 'Register Catering Berhasil', $user);
        // }

        // //return failed with Api Resource
        // return new CateringResource(false, 'Register Catering Gagal!', null);
    }

    public function validateOtp(Request $request){
        request()->validate([
            'email' => 'required',
            'otp' => 'required'
            ]);
        $otp = (new \Ichtrojan\Otp\Otp)->validate(request('email'), request('otp'));

        if(!$token = auth()->attempt($request->only('email', 'password'))){
            return response()->json([
                'message' => 'Password is incorrect'
            ], 401);
        }else if($otp->status){
            $user = User::where('email', request('email'))->first();
            $user->email_verified_at = Carbon::now()->timestamp;
            $user->save();
            return response()->json([
                'status' => $otp->status,
                'token' => $token,
                'message' => 'OTP Validation is success'
            ]);
        }else{
            return response()->json([
                'message' => 'OTP is not valid'
            ], 401);
        }
    }

    public function checkEmailAvail(Request $request){
        request()->validate([
            'email' => ['email', 'required', 'unique:users,email']
            ]);
    }

    public function checkPhoneAvail(Request $request){
        request()->validate([
            'phone' => ['integer', 'unique:customers,phone']
        ]);
    }

}

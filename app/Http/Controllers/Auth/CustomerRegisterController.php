<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Ichtrojan\Otp\Otp;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Mail;

class CustomerRegisterController extends Controller
{
    //
    public function create(Request $request){
        request()->validate([
            'name' => 'required',
            'email' => ['email', 'required', 'unique:users,email'],
            'phone' => ['integer', 'unique:customers,phone'],
            'gender' => ['required'],
            'password' => [
                'required',
                'min:10',             // must be at least 10 characters in length
                'regex:/[0-9]/',      // must contain at least one digit
            ],
            'password_confirmation' => ['required', 'same:password']
        ]);

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => bcrypt(request('password')),
            'type' => 'customer'
        ]);

        Customer::create([
            'user_id' => $user->id,
            'phone' => request('phone'),
            'gender' => request('gender'),
            'points' => 0,
            'balance' => 0
        ]);
        $otp = (new \Ichtrojan\Otp\Otp)->generate(request('email'), 4, 30);
        $otp_token = $otp->token;

        Mail::raw("Your OTP is $otp_token", function ($m) {
            $m->to(request('email'))->subject('Email Verification OTP');
        });

        return $otp;
    }

    public function validateOtp(Request $request){
        request()->validate([
            'email' => 'required',
            'otp' => 'required'
            ]);
        $otp = (new \Ichtrojan\Otp\Otp)->validate(request('email'), intval(request('otp')));

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
}

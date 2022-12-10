<?php

namespace App\Http\Controllers\Api\Catering;

use App\Http\Controllers\Controller;
use App\Http\Resources\CateringResource;
use App\Models\Catering;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class CateringRegisterController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required',
            'address' => 'required',
            'zipcode' => 'required',
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
            'password' => Hash::make($request->password),
            'type' => 'catering'
        ]);

        Catering::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'zipcode' => $request->zipcode,
            'isVerified' => 'no',

        ]);

        if($user) {
            //return with Api Resource
            return new CateringResource(true, 'Register Catering Berhasil', $user);
        }

        //return failed with Api Resource
        return new CateringResource(false, 'Register Catering Gagal!', null);
    }

}

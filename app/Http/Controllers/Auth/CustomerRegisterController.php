<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

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
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CustomerCartController extends Controller
{
    //
    public function createInstant(Request $request){
        $user = auth()->user();
        request()->validate([
            'catering_id' => 'required',
            'products.*.product_id' => 'required',
            'products.*.quantity' => 'required',
        ]);

        $cart = Cart::create([
            'customer_id' => $user->id,
            'order_type' => 'instant',
            'catering_id' => request('catering_id')
        ]);

//        return($cart);

        $products = request('products');
//        return $products;

        foreach ($products as $product){
            $cartDetail = CartDetail::create([
                'cart_id' => $cart->id,
                'product_id' => $product['product_id'],
                'quantity' => $product['quantity']
            ]);
        }

        return response()->json([
            'cart' => $cart
        ]);

    }

    public function getAllCart(Request $request){
        $user = auth()->user();

//        $carts = Cart::where('customer_id', $user->id)->get();
        $carts = DB::table('carts')->select(['carts.id', 'carts.customer_id', 'carts.order_type', 'carts.catering_id', 'caterings.name as catering_name'])->join('caterings', 'caterings.id', '=', 'carts.catering_id')->where('customer_id', $user->id)->get();


        $all_cart_details = array();


        foreach ($carts as $cart){
            $cart_details = DB::table('cart_details')->select(['cart_details.product_id', 'products.name', 'products.price', 'cart_details.quantity'])->join('products', 'products.id', '=', 'cart_details.product_id')->where('cart_id', $cart->id)->get();
//            $all_cart_details[] = $cart_details;
            $cart = (array)$cart;
//            return $cart->context;
            $cart['products'] = $cart_details;
            $all_cart_details[] = $cart;
        }


        return response()->json(["carts" => $all_cart_details]);
    }
}

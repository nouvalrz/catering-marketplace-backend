<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Image;
use App\Models\Orders;
use App\Models\Reviews;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    //
    public function create(Request $request){
        $user = auth()->user();
        $customer = Customer::where('user_id', $user->id)->first();


        if(request('review_image')){
            $photo_resize = \Intervention\Image\Facades\Image::make($request->file('review_image'))->encode('jpg',50);
            $nama_gambar = 'reviews/review-' . md5($photo_resize->__toString()) . '-' . Carbon::now()->format('dmy') . '.jpg';
            Storage::disk('public')->put( $nama_gambar, $photo_resize);

            $image = Image::create(
                [
                    'original_path'=>Storage::url($nama_gambar),
                    'thumbnail_path'=>'null'
                ]
            );

            $review = Reviews::create([
                'order_id' => request('order_id'),
                'customer_id' => $customer->id,
                'star' => request('star'),
                'description' => request('description'),
                'catering_id' => request('catering_id'),
                'has_image' => Storage::url($nama_gambar)
            ]);

            $order = Orders::find(request('order_id'));
            $order->has_review = "1";
            $order->save();

            return response()->json($review);
        }


        $review = Reviews::create([
           'order_id' => request('order_id'),
           'customer_id' => $customer->id,
            'star' => request('star'),
            'description' => request('description'),
            'catering_id' => request('catering_id')
        ]);

        $order = Orders::find(request('order_id'));
        $order->has_review = "1";
        $order->save();

        return response()->json($review);
    }
}

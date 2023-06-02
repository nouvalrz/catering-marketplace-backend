<?php

namespace App\Http\Controllers;

use App\Models\ComplaintImage;
use App\Models\Complaints;
use App\Models\Customer;
use App\Models\Orders;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ComplaintController extends Controller
{
    //
    public function create(Request $request){
        $user = auth()->user();
        $customer = Customer::where('user_id', $user->id)->first();

        $complaint = Complaints::create([
           'orders_id' => request('orders_id'),
           'problem' => request('problem')
        ]);

        $images = $request->file('images');

//        dd($images);

        foreach ($images as $image){
            $photo_resize = \Intervention\Image\Facades\Image::make($image)->encode('jpg',50);
            $nama_gambar = 'complaints/complaint-' . md5($photo_resize->__toString()) . '-' . Carbon::now()->format('dmy') . '.jpg';
            Storage::disk('public')->put( $nama_gambar, $photo_resize);

            $complaintImage = ComplaintImage::create([
                'complaint_id' => $complaint->id,
                'image' => Storage::url($nama_gambar)
            ]);

        }

        $order = Orders::find(request('orders_id'));
        $order->status = "COMPLAINT";
        $order->save();

        return response()->json($complaint);
    }
}

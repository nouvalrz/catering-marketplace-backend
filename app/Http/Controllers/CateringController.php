<?php

namespace App\Http\Controllers;

use App\Models\Catering;
use App\Models\Image;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CateringController extends Controller
{
    //
    public function uploadImage(Request $request){
        request()->validate([
            'catering_id' => 'required',
            'photo'=> 'required|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $photo_resize = \Intervention\Image\Facades\Image::make($request->file('photo'))->resize(115, 115)->encode('jpg',80);
        $nama_gambar = 'caterings/catering-' . md5($photo_resize->__toString()) . '.jpg';
        Storage::disk('public')->put( $nama_gambar, $photo_resize);


        $image = Image::create(
            [
                'original_path'=>Storage::url($nama_gambar),
                'thumbnail_path'=>'null'
            ]
        );

        $catering = Catering::find(request('catering_id'));
        $catering->image_id = $image->id;
        $catering->save();

    }

    public function addProduct(Request $request){
        request()->validate([
            'catering_id' => 'required',
            'name' => 'required',
            'description' => 'required',
            'weight'=>'required',
            'price'=>'required',
            'minimum_quantity'=>'required',
            'maximum_quantity'=>'required',
            'is_free_delivery'=>'required',
            'is_available'=>'required',
            'product_image'=> 'required|image:jpeg,png,jpg,gif,svg|max:2048'
        ]);

        $photo_resize = \Intervention\Image\Facades\Image::make($request->file('product_image'))->resize(150, 150)->encode('jpg',80);
        $nama_gambar = 'products/product-'. request('catering_id') . '-' .time() . '-' . md5($photo_resize->__toString()) . '.jpg';
        Storage::disk('public')->put( $nama_gambar, $photo_resize);


        $image = Image::create(
            [
                'original_path'=>Storage::url($nama_gambar),
                'thumbnail_path'=>'null'
            ]
        );

        $product = Product::create([
            'name' => request('name'),
            'catering_id' => request('catering_id'),
            'description' => request('description'),
            'weight' => request('weight'),
            'price'=> request('price'),
            'minimum_quantity'=>request('minimum_quantity'),
            'maximum_quantity'=>request('maximum_quantity'),
            'is_free_delivery'=>request('is_free_delivery'),
            'is_available'=>request('is_available'),
            'image_id'=>$image->id,
            'is_hidden'=>0
        ]);


    }
}

<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CateringProductImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        for ($i=1; $i<=36 ; $i++){
            $path = "database/seeders/cateringproductimages/{$i}.png";
            $imageFile =  File::get($path);
            $imageResizeFile = \Intervention\Image\Facades\Image::make($imageFile)->resize(300, 300)->encode('jpg',80);
            $imageName = "products/catering-product-{$i}-" . time() . '.jpg';
            Storage::disk('public')->put( $imageName, $imageResizeFile);
            $image = Image::create(
                [
                    'original_path'=>Storage::url($imageName),
                    'thumbnail_path'=>'null'
                ]
            );
        }
    }
}

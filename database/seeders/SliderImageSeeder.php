<?php

namespace Database\Seeders;

use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CateringImageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        for ($i=1; $i<=12 ; $i++){
            $path = "database/seeders/cateringimages/{$i}.png";
            $imageFile =  File::get($path);
            $imageResizeFile = \Intervention\Image\Facades\Image::make($imageFile)->resize(150, 150)->encode('jpg',80);
            $imageName = "caterings/catering_profile_image-{$i}-" . time() . '.jpg';
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

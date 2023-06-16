<?php

namespace Database\Seeders;

use App\Models\Image;
use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CateringProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $productImages = array();

        for ($i=1; $i<=36 ; $i++){
            $path = "database/seeders/cateringproductimages/{$i}.png";
            $imageFile =  File::get($path);
            $imageResizeFile = \Intervention\Image\Facades\Image::make($imageFile)->resize(300, 300)->encode('jpg',80);
            $imageName = "catering-product-{$i}-" . time() . '.jpg';
            $imageNameWithFolder = 'products/' . $imageName;
            Storage::disk('public')->put( $imageNameWithFolder, $imageResizeFile);
            $productImages[] = $imageName;
        }

        $products = array(


            array(
                'name' => "Nasi Kotak Ayam Bakar",
                'description' => "Paket nasi kotak ayam bakar lengkap dengan sate dan pepes",
                'weight' => 650,
                'price' => 28000,
                'minimum_quantity' => 3,
                'maximum_quantity' => 100,
                'is_available' => 1,
                'image' => $productImages[0],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Nasi Kotak Campur",
                'description' => "Paket nasi kotak campur isian ayam suir, mie, dan telur",
                'weight' => 650,
                'price' => 25000,
                'minimum_quantity' => 3,
                'maximum_quantity' => 100,
                'is_available' => 1,
                'image' => $productImages[1],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Sate Kambing Bumbu Kacang",
                'description' => "Sate 20 Tusuk",
                'weight' => 350,
                'price' => 32000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 80,
                'is_available' => 1,
                'image' => $productImages[2],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Nasi Kotak Campur",
                'description' => "Paket lengkap dengan aqua gelas",
                'weight' => 700,
                'price' => 25000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 200,
                'is_available' => 1,
                'image' => $productImages[3],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Nasi Kotak Ayam Bakar Kecap",
                'description' => "Paket nasi ayam bakar kecap dengan lalapan",
                'weight' => 500,
                'price' => 23000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 100,
                'is_available' => 1,
                'image' => $productImages[4],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Nasi Kotak Ayam Bakar Tomat",
                'description' => "Paket nasi ayam bakar tomat dengan selada",
                'weight' => 500,
                'price' => 21000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 200,
                'is_available' => 1,
                'image' => $productImages[5],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Japanese Rice Box",
                'description' => "Paket nasi kotak makanan japanese",
                'weight' => 500,
                'price' => 28000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 120,
                'is_available' => 1,
                'image' => $productImages[6],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Nasi Kotak Rendang",
                'description' => "Paket nasi kotak dengan lauk rendang",
                'weight' => 500,
                'price' => 26000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 100,
                'is_available' => 1,
                'image' => $productImages[7],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Nasi Kotak Lauk Telur",
                'description' => "Paket nasi kotak dengan telur oseng",
                'weight' => 500,
                'price' => 22000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 80,
                'is_available' => 1,
                'image' => $productImages[8],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Ricebowl Ayam Geprek",
                'description' => "Paket ricebowl dengan ayam geprek",
                'weight' => 500,
                'price' => 19000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 120,
                'is_available' => 1,
                'image' => $productImages[9],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Jajan Kotak 8rb",
                'description' => "Isi jajan risoles, pie dan yang lainnya",
                'weight' => 200,
                'price' => 8000,
                'minimum_quantity' => 20,
                'maximum_quantity' => 180,
                'is_available' => 1,
                'image' => $productImages[10],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Jajan Kotak 6rb",
                'description' => "Isi kue dan jajanan tradisional",
                'weight' => 200,
                'price' => 6000,
                'minimum_quantity' => 20,
                'maximum_quantity' => 200,
                'is_available' => 1,
                'image' => $productImages[11],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Nasi Tumpeng Besar",
                'description' => "Nasi tumpeng lengkap dengan isiian lauk",
                'weight' => 4000,
                'price' => 128000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 5,
                'is_available' => 1,
                'image' => $productImages[12],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Nasi Tumpeng Box",
                'description' => "Nasi tumpeng paketan box",
                'weight' => 700,
                'price' => 24000,
                'minimum_quantity' => 5,
                'maximum_quantity' => 50,
                'is_available' => 1,
                'image' => $productImages[13],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Ayam Goreng Kalasan",
                'description' => "Paket ayam kalasan setengah ekor",
                'weight' => 550,
                'price' => 37000,
                'minimum_quantity' => 3,
                'maximum_quantity' => 100,
                'is_available' => 1,
                'image' => $productImages[14],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Nasi Bekel Telur",
                'description' => "Nasi bekel dengan isian telur",
                'weight' => 400,
                'price' => 18000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 100,
                'is_available' => 1,
                'image' => $productImages[15],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Tumis Tempe Kacang Panjang Box",
                'description' => "Dijual dalam box ukuran 500gr",
                'weight' => 550,
                'price' => 32000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 100,
                'is_available' => 1,
                'image' => $productImages[16],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Ayam Sisit Sambal Matah Box",
                'description' => "Dijual dalam box ukuran 500gr",
                'weight' => 550,
                'price' => 45000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 100,
                'is_available' => 1,
                'image' => $productImages[17],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Rendang Sapi Box",
                'description' => "Dijual dalam box ukuran 500gr",
                'weight' => 550,
                'price' => 80000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 20,
                'is_available' => 1,
                'image' => $productImages[18],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Sambal Ati Box",
                'description' => "Dijual dalam box ukuran 500gr",
                'weight' => 550,
                'price' => 62000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 20,
                'is_available' => 1,
                'image' => $productImages[19],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Lumpia Box",
                'description' => "Dijual lumpia jumbo dalam box berisi 4pcs",
                'weight' => 550,
                'price' => 32000,
                'minimum_quantity' => 3,
                'maximum_quantity' => 50,
                'is_available' => 1,
                'image' => $productImages[20],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Tumis Tauge Box",
                'description' => "Dijual dalam box ukuran 500gr",
                'weight' => 550,
                'price' => 22000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 50,
                'is_available' => 1,
                'image' => $productImages[21],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Oseng Buncis Tempe Box",
                'description' => "Dijual dalam box ukuran 500gr",
                'weight' => 550,
                'price' => 30000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 50,
                'is_available' => 1,
                'image' => $productImages[22],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Pepes Ayam Bumbu Kuning Box",
                'description' => "Dijual dalam box berisi 3pcs",
                'weight' => 550,
                'price' => 25000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 50,
                'is_available' => 1,
                'image' => $productImages[23],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Siomay Ikan Tenggiri",
                'description' => "Paket Siomay dengan ikan tenggiri",
                'weight' => 400,
                'price' => 25000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 50,
                'is_available' => 1,
                'image' => $productImages[24],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Peyek Kacang Teri Toples",
                'description' => "Peyek kacang teri dalam toples 5liter",
                'weight' => 1100,
                'price' => 50000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 50,
                'is_available' => 1,
                'image' => $productImages[25],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Rendang Ayam Box",
                'description' => "Dijual dalam bentuk box ukuran 500gr",
                'weight' => 550,
                'price' => 75000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 50,
                'is_available' => 1,
                'image' => $productImages[26],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Bubur Campur Santan",
                'description' => "Isiian kacang merah, kacang hijau dan ketan merah",
                'weight' => 350,
                'price' => 12000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 200,
                'is_available' => 1,
                'image' => $productImages[27],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Rujak Bali Toples Kecil",
                'description' => "Isi campuran buah",
                'weight' => 350,
                'price' => 22000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 200,
                'is_available' => 1,
                'image' => $productImages[28],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Capcay Box",
                'description' => "Capcay dalam box ukuran 500gr",
                'weight' => 550,
                'price' => 29000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 200,
                'is_available' => 1,
                'image' => $productImages[29],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Tumis Labu Siam Box",
                'description' => "Tumis labu siam dalam box ukuran 500gr",
                'weight' => 550,
                'price' => 34000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 200,
                'is_available' => 1,
                'image' => $productImages[30],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Nasi Ayam Bakar Besar",
                'description' => "Isian ayam dengan kemangi",
                'weight' => 650,
                'price' => 18000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 200,
                'is_available' => 1,
                'image' => $productImages[31],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Sayur Tumis Timun",
                'description' => "Dengan kemasan box ukuran 500gr",
                'weight' => 550,
                'price' => 23000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 200,
                'is_available' => 1,
                'image' => $productImages[32],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Biji Salak Warna-Warni",
                'description' => "Dijual dalam kemasan mangkok",
                'weight' => 350,
                'price' => 12000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 200,
                'is_available' => 1,
                'image' => $productImages[33],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Rendang Kentang Box",
                'description' => "Dijual rendang box ukuran 500gr",
                'weight' => 350,
                'price' => 48000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 200,
                'is_available' => 1,
                'image' => $productImages[34],
                'catering_id'=>1,
                'category_id' => 1
            ),
            array(
                'name' => "Capcay Brokoli Jamur",
                'description' => "Dijual box ukuran 500gr",
                'weight' => 350,
                'price' => 38000,
                'minimum_quantity' => 1,
                'maximum_quantity' => 200,
                'is_available' => 1,
                'image' => $productImages[35],
                'catering_id'=>1,
                'category_id' => 1
            )

            );

//        print_r($products);

        for($i=1; $i<=31; $i++){
            $range = range(0, 35);
            shuffle($range);
            $n = 7;
            $randomProducts = array_slice($range, 0 , $n);

//            print_r($randomProducts);

            foreach ($randomProducts as $randomProduct){
                $products[$randomProduct]['catering_id'] = $i;
                print_r($products[$randomProduct]);
                Product::create($products[$randomProduct]);
            }
        }
    }
}

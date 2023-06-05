<?php

namespace Database\Seeders;

use App\Models\Catering;
use App\Models\Image;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class CateringSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //

        $cateringImages = array();

        for ($i=1; $i<=12 ; $i++){
            $path = "database/seeders/cateringimages/{$i}.png";
            $imageFile =  File::get($path);
            $imageResizeFile = \Intervention\Image\Facades\Image::make($imageFile)->resize(150, 150)->encode('jpg',80);
            $imageName = "catering_profile_image-{$i}-" . time() . '.jpg';
            $imageNameWithFolder = "caterings/" . $imageName;
            Storage::disk('public')->put( $imageNameWithFolder, $imageResizeFile);
            $cateringImages[] = $imageName;
        }

        $caterings =
            [
            [
                "name"=>"Vegan Food Nayla",
                "email"=>"14244@gmail.com",
                "description"=>"Menjual aneka ragam makanan vegetarian",
                "phone"=>"628518772829",
                "address"=>"Jalan Dukuh Sari Gg. Telaga Sari No.15",
                "zipcode"=>80223,
                "latitude"=>-8.694457,
                "longitude"=>115.217101,
                "delivery_start_time"=>"09:00:00",
                "delivery_end_time"=>"18:00:00",
                "village_id"=>5171010003,
                "user_id" =>1,
                "image" => $cateringImages[array_rand($cateringImages)],
                "categories" => [
                    1,7
                ]
            ],
            [
                "name"=>"Mecca Catering",
                "email"=>"2@gmail.com",
                "description"=>"Aneka makanan khas timur tengah",
                "phone"=>"628736628281",
                "address"=>"Jl. Gunung Bromo gang XIB No.2, Tegal Kertha",
                "zipcode"=>80119,
                "latitude"=>-8.663498,
                "longitude"=>115.196198,
                "delivery_start_time"=>"11:00:00",
                "delivery_end_time"=>"20:00:00",
                "village_id"=>5171030009,
                "user_id" =>2,
                "image" => $cateringImages[array_rand($cateringImages)],
                "categories" => [
                    1,7
                ]
            ],
            [
                "name"=>"OMA THIA'S Catering",
                "email"=>"3@gmail.com",
                "description"=>"All about chineese food",
                "phone"=>"628155749500",
                "address"=>"Gg. Puri Ratu No.23, Padangsambian Klod",
                "zipcode"=>80117,
                "latitude"=>-8.667153,
                "longitude"=>115.178537,
                "delivery_start_time"=>"10:00:00",
                "delivery_end_time"=>"19:00:00",
                "village_id"=>5171030010,
                "user_id" =>3,
                "image" => $cateringImages[array_rand($cateringImages)],
                "categories" => [
                    1, 7
                ]
            ],
            [
                "name"=>"MAKANOPO catering bali",
                "email"=>"4@gmail.com",
                "description"=>"Menyediakan katering masakan jawa",
                "phone"=>"6285782961392",
                "address"=>"Jl. Mekar II Blk. A VII No.100, Pemogan",
                "zipcode"=>80221,
                "latitude"=>-8.703000,
                "longitude"=>15.204718,
                "delivery_start_time"=>"08:00:00",
                "delivery_end_time"=>"17:00:00",
                "village_id"=>5171010001,
                "user_id" =>4,
                "image" => $cateringImages[array_rand($cateringImages)],
                "categories" => [
                    1, 4
                ]
            ],
            [
                "name"=>"Harwandi Catering",
                "email"=>"5@gmail.com",
                "description"=>"Segala jenis makanan Indonesia",
                "phone"=>"6281246585097",
                "address"=>"Jl. Padang Tawang Gg. VII No.2, Canggu",
                "zipcode"=>80361,
                "latitude"=>-8.618443,
                "longitude"=>115.155004,
                "delivery_start_time"=>"11:00:00",
                "delivery_end_time"=>"21:00:00",
                "village_id"=>5103030005,
                "user_id" =>5,
                "image" => $cateringImages[array_rand($cateringImages)],
                "categories" => [
                    1, 7
                ]
            ],
            [
                "name"=>"Wr. Zaenah Renon",
                "email"=>"6@gmail.com",
                "description"=>"Menyediakan Nasi Kotak untuk Acara Besar",
                "phone"=>"6281337332335",
                "address"=>"Jl. Letda Tantular No.41B, Dangin Puri Klod, Kec. Denpasar Tim., Kota Denpasar, Bali",
                "zipcode"=>80232,
                "latitude"=>-8.666769,
                "longitude"=>115.222863,
                "delivery_start_time"=>"08:00:00",
                "delivery_end_time"=>"21:00:00",
                "village_id"=>5171020001,
                "user_id" =>6,
                "image" => $cateringImages[array_rand($cateringImages)],
                "categories" => [
                    1
                ]
            ],
            [
                "name"=>"Surya Jaya Catering Bali",
                "email"=>"7@gmail.com",
                "description"=>"Nasi Kotak dengan Menu Indonesia",
                "phone"=>"6281805617038",
                "address"=>"Jl. Tukad Irawadi No.20, Panjer, Denpasar Selatan, Kota Denpasar, Bali",
                "zipcode"=>80225,
                "latitude"=>-8.679910,
                "longitude"=> 115.220858,
                "delivery_start_time"=>"08:00:00",
                "delivery_end_time"=>"18:00:00",
                "village_id"=>5171010006,
                "user_id" =>7,
                "image" => $cateringImages[array_rand($cateringImages)],
                "categories" => [
                    1
                ]
            ],
            [
                "name"=>"Lumbung Catering",
                "email"=>"8@gmail.com",
                "description"=>"Menyediakan jasa catering western food",
                "phone"=>"6281239277720",
                "address"=>"Gg. IV No.25D, Dauh Puri Klod, Kec. Denpasar Bar., Kota Denpasar, Bali",
                "zipcode"=>80223,
                "latitude"=>-8.680966,
                "longitude"=> 115.209768,
                "delivery_start_time"=>"11:00:00",
                "delivery_end_time"=>"22:00:00",
                "village_id"=>5171020001,
                "user_id" =>8,
                "image" => $cateringImages[array_rand($cateringImages)],
                "categories" => [
                    1, 6
                ]
            ],
            [
                "name"=>"Sri Krisna Catering",
                "email"=>"9@gmail.com",
                "description"=>"Menyediakan menu olahan lokal Bali",
                "phone"=>"6287862236788",
                "address"=>"Jl. Banteng Gg. VII No.30, Dangin Puri Kaja, Kec. Denpasar Utara, Kota Denpasar, Bali 80234",
                "zipcode"=>80234,
                "latitude"=>-8.645416,
                "longitude"=> 115.220663,
                "delivery_start_time"=>"09:00:00",
                "delivery_end_time"=>"19:00:00",
                "village_id"=>5171031004,
                "user_id" =>9,
                "image" => $cateringImages[array_rand($cateringImages)],
                "categories" => [
                    1, 3
                ]
            ],
            [
                "name"=>"Catering Kambing Guling Ade Muzni",
                "email"=>"10@gmail.com",
                "description"=>"Menjual dan menyediakan jasa katering menu olahan kambing",
                "phone"=>"62818250403",
                "address"=>"Jalan Kerta Raharja V no.54, Sidakarya, Denpasar Selatan, Kota Denpasar, Bali",
                "zipcode"=>80224,
                "latitude"=>-8.708487,
                "longitude"=>  115.235761,
                "delivery_start_time"=>"08:30:00",
                "delivery_end_time"=>"17:30:00",
                "village_id"=>5171010005,
                "user_id" =>10,
                "image" => $cateringImages[array_rand($cateringImages)],
                "categories" => [
                    1, 4
                ]
            ],
            [
                "name"=>"Catering Service Ny. Warti Buleleng",
                "email"=>"11@gmail.com",
                "description"=>"Menyediakan makanan khas Singaraja dan jajanan Bali",
                "phone"=>"62818250403",
                "address"=>"JL. Ganetri, 4, Gatot Subroto Timur, Tonja, Kec. Denpasar Utara, Kota Denpasar, Bali",
                "zipcode"=>80235,
                "latitude"=>-8.636810,
                "longitude"=> 115.232531,
                "delivery_start_time"=>"08:00:00",
                "delivery_end_time"=>"16:00:00",
                "village_id"=>5171031006,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>11,
                "categories" => [
                    1, 3
                ]
            ],
            [
                "name"=>"Catering Kita Bali",
                "email"=>"12@gmail.com",
                "description"=>"Menyediakan makanan Indonesia",
                "phone"=>"628113955503",
                "address"=>"Jl. Suli No.25, Dangin Puri Kangin, Kec. Denpasar Utara, Kota Denpasar, Bali",
                "zipcode"=>80233,
                "latitude"=>-8.646862,
                "longitude"=> 115.222841,
                "delivery_start_time"=>"07:00:00",
                "delivery_end_time"=>"21:00:00",
                "village_id"=>5171030006,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>12,
                "categories" => [
                    1, 3
                ]
            ],
            [
                "name"=>"Endro Catering Bali",
                "email"=>"13@gmail.com",
                "description"=>"Menyediakan makanan katering untuk acara",
                "phone"=>"6285156560926",
                "address"=>"Jalan Perum Buana Permai Blok 1C No. 21, Padang Sambian, Denpasar Barat, Kota Denpasar, Bali",
                "zipcode"=>80117,
                "latitude"=>-8.659405,
                "longitude"=> 115.185428,
                "delivery_start_time"=>"09:00:00",
                "delivery_end_time"=>"18:00:00",
                "village_id"=>5171030010,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>13,
                "categories" => [
                    1, 7
                ]
            ],
            [
                "name"=>"Warung Wahai Catering",
                "email"=>"14@gmail.com",
                "description"=>"Menyediakan makanan nasi campur Indonesia",
                "phone"=>"6281999072415",
                "address"=>"Jl. Gn. Catur VIII No.15, Padangsambian Kaja, Kec. Denpasar Bar., Kota Denpasar, Bali",
                "zipcode"=>80116,
                "latitude"=>-8.633263,
                "longitude"=>  115.191195,
                "delivery_start_time"=>"09:00:00",
                "delivery_end_time"=>"21:00:00",
                "village_id"=>5171030011,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>14,
                "categories" => [
                    1, 4
                ]
            ],
            [
                "name"=>"Catering Ibu Misna",
                "email"=>"15@gmail.com",
                "description"=>"Menyediakan makanan nasi tumpeng dan nasi lainnya",
                "phone"=>"6287861700022",
                "address"=>"Jl. Gunung Guntur Gg. Taman Sari II No.14, Padangsambian, Kec. Denpasar Bar., Kota Denpasar, Bali",
                "zipcode"=>80117,
                "latitude"=>-8.653484,
                "longitude"=>  115.181873,
                "delivery_start_time"=>"10:00:00",
                "delivery_end_time"=>"19:00:00",
                "village_id"=>5171030010,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>5,
                "categories" => [
                    1, 4
                ]
            ],
            [
                "name"=>"Karisma Boga Catering",
                "email"=>"16@gmail.com",
                "description"=>"Menyediakan katering untuk acara",
                "phone"=>"6282244742745",
                "address"=>"Jl. Taman Sekar, Padangsambian, Kec. Denpasar Bar., Kota Denpasar, Bali",
                "zipcode"=>80117,
                "latitude"=>-8.654842,
                "longitude"=>  115.183246,
                "delivery_start_time"=>"09:00:00",
                "delivery_end_time"=>"21:00:00",
                "village_id"=>5171030010,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>16,
                "categories" => [
                    1, 7
                ]
            ],
            [
                "name"=>"Dapur Pak Lik",
                "email"=>"17@gmail.com",
                "description"=>"Menjual Lalapan dan Ayam Betutu Khas Bali",
                "phone"=>"6281805410733",
                "address"=>"Gg. Ikan Duyung No.4B, Sesetan, Denpasar Selatan, Kota Denpasar, Bali",
                "zipcode"=>80221,
                "latitude"=>-8.707431,
                "longitude"=>  115.225485,
                "delivery_start_time"=>"08:00:00",
                "delivery_end_time"=>"17:30:00",
                "village_id"=>5171010003,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>17,
                "categories" => [
                    1, 3
                ]
            ],

            [
                "name"=>"Catering Moro Asih",
                "email"=>"18@gmail.com",
                "description"=>"Menyiapkan layanan katering untuk acara pernikahan",
                "phone"=>"6281246751515",
                "address"=>"Jl. Tirta Nadi II No.21, Sanur Kauh, Denpasar Selatan, Kota Denpasar, Bali",
                "zipcode"=>80227,
                "latitude"=>-8.700137,
                "longitude"=>  115.253940,
                "delivery_start_time"=>"08:00:00",
                "delivery_end_time"=>"21:00:00",
                "village_id"=>5171010008,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>18,
                "categories" => [
                    1, 3
                ]
            ],
            [
                "name"=>"Laela - Catering Service",
                "email"=>"19@gmail.com",
                "description"=>"Layanan katering untuk seluruh kegiatan acara",
                "phone"=>"361480891",
                "address"=>"Jl. Subur Gg. Mirah Mandiri No.5, Pemecutan Klod, Kec. Denpasar Bar., Kota Denpasar, Bali",
                "zipcode"=>80112,
                "latitude"=>-8.668117,
                "longitude"=>  115.203205,
                "delivery_start_time"=>"08:00:00",
                "delivery_end_time"=>"17:00:00",
                "village_id"=>5171030002,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>19,
                "categories" => [
                    1, 7
                ]
            ],
            [
                "name"=>"Dewata Catering",
                "email"=>"20@gmail.com",
                "description"=>"Layanan katering untuk seluruh kegiatan acara",
                "phone"=>"628976600999",
                "address"=>"Jl. Pura Banyu Kuning I No.2, Padangsambian, Kec. Denpasar Bar., Kota Denpasar, Bali",
                "zipcode"=>80119,
                "latitude"=>-8.679651,
                "longitude"=>  115.189049,
                "delivery_start_time"=>"09:00:00",
                "delivery_end_time"=>"22:00:00",
                "village_id"=>5171030010,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>20,
                "categories" => [
                    1, 3
                ]
            ],
            [
                "name"=>"Damar Bali Catering",
                "email"=>"21@gmail.com",
                "description"=>"Menyediakan jasa katering, prasmanan, nasi kotak dan yang lainnya",
                "phone"=>"6289688922212",
                "address"=>"Gg. Pipit, Sidakarya, Denpasar Selatan, Kota Denpasar, Bali",
                "zipcode"=>80225,
                "latitude"=>-8.694502,
                "longitude"=>   115.228496,
                "delivery_start_time"=>"09:00:00",
                "delivery_end_time"=>"22:00:00",
                "village_id"=>5171010005,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>21,
                "categories" => [
                    1, 7
                ]
            ],

            [
                "name"=>"Kedai Hauce 69",
                "email"=>"22@gmail.com",
                "description"=>"Menyediakan jasa katering makanan chinese",
                "phone"=>"6289688922212",
                "address"=>"Jl. Zidam IV Gg. Saka Guru No.III, Pemogan, Denpasar Selatan, Kota Denpasar, Bali",
                "zipcode"=>80221,
                "latitude"=>-8.713487,
                "longitude"=>   115.201545,
                "delivery_start_time"=>"10:00:00",
                "delivery_end_time"=>"22:00:00",
                "village_id"=>5171010001,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>22,
                "categories" => [
                    1, 7
                ]
            ],

            [
                "name"=>"Dira Ayu Katering",
                "email"=>"23@gmail.com",
                "description"=>"Terima Pesanan Nasi Tumpeng Kuning / Putih Enak Murah Halal Renon bali indonesia",
                "phone"=>"6281237304763",
                "address"=>"Jl. Ciung Wanara VI No.6, Renon, Denpasar Selatan, Kota Denpasar, Bali",
                "zipcode"=>80234,
                "latitude"=>-8.676908,
                "longitude"=>  115.234517,
                "delivery_start_time"=>"10:00:00",
                "delivery_end_time"=>"22:00:00",
                "village_id"=>5171010007,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>23,
                "categories" => [
                    1, 4
                ]
            ],

            [
                "name"=>"CateringBali",
                "email"=>"24@gmail.com",
                "description"=>"Jasa Wedding Catering",
                "phone"=>"6282145670077",
                "address"=>"Jl. Akasia V No. 18 U, Sumerta, Denpasar Timur, Kota Denpasar, Bali",
                "zipcode"=>80235,
                "latitude"=>-8.656421,
                "longitude"=>  115.240336,
                "delivery_start_time"=>"07:00:00",
                "delivery_end_time"=>"22:00:00",
                "village_id"=>5171020006,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>24,
                "categories" => [
                    1, 7
                ]
            ],

            [
                "name"=>"Widya Catering",
                "email"=>"25@gmail.com",
                "description"=>"Menyediakan nasi kotak daily katering",
                "phone"=>"6281337119671",
                "address"=>"Gg. Sukomulyo No.5a, Dauh Puri Kauh, Kec. Denpasar Bar., Kota Denpasar, Bali",
                "zipcode"=>80113,
                "latitude"=>-8.683595,
                "longitude"=> 115.200960,
                "delivery_start_time"=>"07:00:00",
                "delivery_end_time"=>"19:00:00",
                "village_id"=>5171030004,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>25,
                "categories" => [
                    1, 4
                ]
            ],

            [
                "name"=>"Widya Catering",
                "email"=>"26@gmail.com",
                "description"=>"Menyediakan nasi kotak daily katering",
                "phone"=>"6281337119671",
                "address"=>"Gg. Sukomulyo No.5a, Dauh Puri Kauh, Kec. Denpasar Bar., Kota Denpasar, Bali",
                "zipcode"=>80113,
                "latitude"=>-8.683595,
                "longitude"=> 115.200960,
                "delivery_start_time"=>"07:00:00",
                "delivery_end_time"=>"19:00:00",
                "village_id"=>5171030004,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>26,
                "categories" => [
                    1, 7
                ]
            ],

            [
                "name"=>"Catering Bali Presto",
                "email"=>"27@gmail.com",
                "description"=>"Menyediakan nasi kotak daily katering",
                "phone"=>"6283119319943",
                "address"=>"Jalan Taman Sekar XC, Padangsambian, Denpasar Barat, Denpasar City, Bali",
                "zipcode"=>80117,
                "latitude"=>-8.651617,
                "longitude"=> 115.184233,
                "delivery_start_time"=>"07:00:00",
                "delivery_end_time"=>"15:00:00",
                "village_id"=>5171030010,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>27,
                "categories" => [
                    1, 3
                ]
            ],

            [
                "name"=>"Sariyoga Catering Service",
                "email"=>"28@gmail.com",
                "description"=>"Menyediakan nasi kotak daily katering",
                "phone"=>"361255816",
                "address"=>"Jl. Tukad Batanghari No.89, Dauh Puri Klod, Kec. Denpasar Bar., Kota Denpasar, Bali",
                "zipcode"=>80225,
                "latitude"=>-8.686290,
                "longitude"=>115.230448,
                "delivery_start_time"=>"08:00:00",
                "delivery_end_time"=>"17:00:00",
                "village_id"=>5171030004,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>28,
                "categories" => [
                    1, 3
                ]
            ],

            [
                "name"=>"Kayana Bali Catering",
                "email"=>"29@gmail.com",
                "description"=>"Menyediakan jasa prasmanan acara",
                "phone"=>"6281239439878",
                "address"=>"Jl. Mahendradatta No.55 B, Padangsambian, Kec. Denpasar Bar., Kota Denpasar, Bali",
                "zipcode"=>80119,
                "latitude"=>-8.665340,
                "longitude"=>115.189852,
                "delivery_start_time"=>"08:00:00",
                "delivery_end_time"=>"21:00:00",
                "village_id"=>5171030010,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>29,
                "categories" => [
                    1, 7
                ]
            ],

            [
                "name"=>"Katering Kenzie Kitchen (K3)",
                "email"=>"30@gmail.com",
                "description"=>"Menyediakan nasi kotak masakan Indonesia",
                "phone"=>"6287860978023",
                "address"=>"Gg. Mawar III No.1, Ubung, Kec. Denpasar Utara, Kota Denpasar, Bali",
                "zipcode"=>80115,
                "latitude"=>-8.635619,
                "longitude"=>115.207592,
                "delivery_start_time"=>"08:00:00",
                "delivery_end_time"=>"17:00:00",
                "village_id"=>5171031009,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>30,
                "categories" => [
                    1, 7
                ]
            ],

            [
                "name"=>"Katering Kenzie Kitchen (K3)",
                "email"=>"31@gmail.com",
                "description"=>"Menyediakan nasi kotak masakan Indonesia",
                "phone"=>"6287860978023",
                "address"=>"Gg. Mawar III No.1, Ubung, Kec. Denpasar Utara, Kota Denpasar, Bali",
                "zipcode"=>80115,
                "latitude"=>-8.635619,
                "longitude"=>115.207592,
                "delivery_start_time"=>"08:00:00",
                "delivery_end_time"=>"17:00:00",
                "village_id"=>5171031009,
                "image" => $cateringImages[array_rand($cateringImages)],
                "user_id" =>31,
                "categories" => [
                    1, 7
                ]
            ]
                ];


        $cateringsWithoutCat = array();

        foreach ($caterings as $catering){
            unset($catering["categories"]);
            array_push($cateringsWithoutCat, $catering);
        }

//        print_r($caterings[1]["categories"]);

        DB::table("caterings")->insert($cateringsWithoutCat);


        $cateringDataOnDB = Catering::all();

        foreach ($cateringDataOnDB as $index=>$catering){
//            print $index;
            $catering->categories()->attach($caterings[$index]["categories"]);
        }



    }
}

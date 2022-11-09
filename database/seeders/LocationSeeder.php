<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\District;
use App\Models\Province;
use Illuminate\Database\Seeder;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        Province::truncate();
        City::truncate();
        District::truncate();

        $province = Province::create(['name'=>'Bali']);
        $badung = City::create(['province_id'=>$province->id, 'name'=>'Kab. Badung']);
        District::create(['city_id'=>$badung->id, 'name'=>'Abiansemal']);
        District::create(['city_id'=>$badung->id, 'name'=>'Kuta']);
        District::create(['city_id'=>$badung->id, 'name'=>'Kuta Selatan']);
        District::create(['city_id'=>$badung->id, 'name'=>'Kuta Utara']);
        District::create(['city_id'=>$badung->id, 'name'=>'Mengwi']);
        District::create(['city_id'=>$badung->id, 'name'=>'Petang']);
        District::create(['city_id'=>$badung->id, 'name'=>'Mangupura']);

        $denpasar = City::create(['province_id'=>$province->id, 'name'=>'Kota Denpasar']);
        District::create(['city_id'=>$denpasar->id, 'name'=>'Denpasar Utara']);
        District::create(['city_id'=>$denpasar->id, 'name'=>'Denpasar Selatan']);
        District::create(['city_id'=>$denpasar->id, 'name'=>'Denpasar Barat']);
        District::create(['city_id'=>$denpasar->id, 'name'=>'Denpasar Timur']);


        $bangli = City::create(['province_id'=>$province->id, 'name'=>'Kab. Bangli']);
        District::create(['city_id'=>$bangli->id, 'name'=>'Bangli']);
        District::create(['city_id'=>$bangli->id, 'name'=>'Kintamani']);
        District::create(['city_id'=>$bangli->id, 'name'=>'Susut']);
        District::create(['city_id'=>$bangli->id, 'name'=>'Tembuku']);

        $buleleng = City::create(['province_id'=>$province->id, 'name'=>'Kab. Buleleng']);
        District::create(['city_id'=>$buleleng->id, 'name'=>'Singaraja']);
        District::create(['city_id'=>$buleleng->id, 'name'=>'Banjar']);
        District::create(['city_id'=>$buleleng->id, 'name'=>'Buleleng']);
        District::create(['city_id'=>$buleleng->id, 'name'=>'Busung Biu']);
        District::create(['city_id'=>$buleleng->id, 'name'=>'Gerogak']);
        District::create(['city_id'=>$buleleng->id, 'name'=>'Kubutambahan']);
        District::create(['city_id'=>$buleleng->id, 'name'=>'Sawan']);
        District::create(['city_id'=>$buleleng->id, 'name'=>'Seririt']);
        District::create(['city_id'=>$buleleng->id, 'name'=>'Sukasada']);
        District::create(['city_id'=>$buleleng->id, 'name'=>'Tejakula']);

        $gianyar = City::create(['province_id'=>$province->id, 'name'=>'Kab. Gianyar']);
        District::create(['city_id'=>$gianyar->id, 'name'=>'Gianyar']);
        District::create(['city_id'=>$gianyar->id, 'name'=>'Blahbatuh']);
        District::create(['city_id'=>$gianyar->id, 'name'=>'Sukawati']);
        District::create(['city_id'=>$gianyar->id, 'name'=>'Tampaksiring']);
        District::create(['city_id'=>$gianyar->id, 'name'=>'Tegallalang']);
        District::create(['city_id'=>$gianyar->id, 'name'=>'Ubud']);

        $jembarna = City::create(['province_id'=>$province->id, 'name'=>'Kab. Jembrana']);
        District::create(['city_id'=>$jembarna->id, 'name'=>'Negara']);
        District::create(['city_id'=>$jembarna->id, 'name'=>'Melaya']);
        District::create(['city_id'=>$jembarna->id, 'name'=>'Jembrana']);
        District::create(['city_id'=>$jembarna->id, 'name'=>'Mendoyo']);
        District::create(['city_id'=>$jembarna->id, 'name'=>'Pekutatan']);

        $karangasem = City::create(['province_id'=>$province->id, 'name'=>'Kab. Karangasem']);
        District::create(['city_id'=>$karangasem->id, 'name'=>'Amlapura']);
        District::create(['city_id'=>$karangasem->id, 'name'=>'Karangasem']);
        District::create(['city_id'=>$karangasem->id, 'name'=>'Bebandem']);
        District::create(['city_id'=>$karangasem->id, 'name'=>'Kubu']);
        District::create(['city_id'=>$karangasem->id, 'name'=>'Manggis']);
        District::create(['city_id'=>$karangasem->id, 'name'=>'Rendang']);
        District::create(['city_id'=>$karangasem->id, 'name'=>'Selat']);
        District::create(['city_id'=>$karangasem->id, 'name'=>'Sidemen']);
        District::create(['city_id'=>$karangasem->id, 'name'=>'Abang']);

        $klungkung = City::create(['province_id'=>$province->id, 'name'=>'Kab. Klungkung']);
        District::create(['city_id'=>$klungkung->id, 'name'=>'Semarapura']);
        District::create(['city_id'=>$klungkung->id, 'name'=>'Banjarangkan']);
        District::create(['city_id'=>$klungkung->id, 'name'=>'Dawan']);
        District::create(['city_id'=>$klungkung->id, 'name'=>'Klungkung']);
        District::create(['city_id'=>$klungkung->id, 'name'=>'Nusa Penida']);

        $tabanan = City::create(['province_id'=>$province->id, 'name'=>'Kab. Tabanan']);
        District::create(['city_id'=>$tabanan->id, 'name'=>'Tabanan']);
        District::create(['city_id'=>$tabanan->id, 'name'=>'Baturiti']);
        District::create(['city_id'=>$tabanan->id, 'name'=>'Kediri']);
        District::create(['city_id'=>$tabanan->id, 'name'=>'Kerambitan']);
        District::create(['city_id'=>$tabanan->id, 'name'=>'Marga']);
        District::create(['city_id'=>$tabanan->id, 'name'=>'Penebel']);
        District::create(['city_id'=>$tabanan->id, 'name'=>'Pupuan']);
        District::create(['city_id'=>$tabanan->id, 'name'=>'Selemadeg']);
        District::create(['city_id'=>$tabanan->id, 'name'=>'Selemadeg Barat']);
        District::create(['city_id'=>$tabanan->id, 'name'=>'Selemadeg Timur']);

    }
}

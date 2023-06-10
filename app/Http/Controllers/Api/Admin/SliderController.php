<?php
namespace App\Http\Controllers\Api\Admin;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SliderResource;
use App\Http\Resources\SliderResourceAdmin;
use App\Models\Categories;
use App\Models\Slider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(request()->active == 'active'){
            $slider = Slider::where('is_active', 1)->when(request()->q,
            function($slider) {
                $slider = $slider->where('title', 'like', '%'. request()->q . '%');
            })->latest()->paginate(request()->pages);
            //return with Api Resource
            return new SliderResource(true, 'List Data Slider', $slider);
        }else if(request()->active == 'non'){
            $slider = Slider::where('is_active', 0)->when(request()->q,
            function($slider) {
                $slider = $slider->where('title', 'like', '%'. request()->q . '%');
            })->latest()->paginate(request()->pages);
            //return with Api Resource
            return new SliderResource(true, 'List Data Slider', $slider);
        }
        $linkImage = asset('storage/sliders/');

        $slider = Slider::when(request()->q,
        function($slider) {
            $slider = $slider->where('title', 'like', '%'. request()->q . '%');
        })->latest()->paginate(request()->pages);
        //return with Api Resource
        return new SliderResourceAdmin(true, 'List Data Slider', $slider, $linkImage);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $userId = auth()->guard('api_catering')->user()->id;
        // $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        $validator = Validator::make($request->all(), [
            'image' => 'required|image|mimes:jpeg,jpg,png|max:10000',
            'title' => 'required',
            'is_active' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //upload image
        $image = $request->file('image');
        $image->storeAs('public/sliders', $image->hashName());
        //create product
        $slider = Slider::create([
            'image' => $image->hashName(),
            'title' => $request->title,
            'is_active' => $request->is_active,


        ]);
        if($slider) {
            //return success with Api Resource
            return new SliderResource(true, 'Data Slider Berhasil Disimpan!', $slider);
        }
        //return failed with Api Resource
        return new SliderResource(false, 'Data Slider Gagal Disimpan!', null);
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        // $userId = auth()->guard('api_catering')->user()->id;
        // $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        
        $slider = Slider::whereId($id)->first();
        if($slider) {
            //return success with Api Resource
            return new SliderResource(true, 'Detail Data Slider!', $slider);
        }
        //return failed with Api Resource
        return new SliderResource(false, 'Detail Data Slider Tidak Ditemukan!', null);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider)
    {
        
        $validator = Validator::make($request->all(), [
            // 'image' =>[Rule::requiredIf(function (){
            //     if (Product::whereNotNull('image')->where('id', $idProd)->exists()) {
            //        return false;
            //     }
            //       return true;
            //    }),'image','mimes:jpeg,png,jpg','max:500'],
            // 'image' => 'image|mimes:jpeg,jpg,png|max:500|dimensions:ratio=1/1',
            'title' => 'required',
            'is_active' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //check image update
        if ($request->file('image')) {
            //remove old image
            Storage::disk('local')->delete('public/sliders/'.basename($slider->image));
            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/sliders', $image->hashName());
            //update product with new image
            $slider->update([
                'image' => $image->hashName(),
                'title' => $request->title,
                'is_active' => $request->is_active,

            ]);
        }else{
            //update product without image
            $slider->update([
                'title' => $request->title,
                'is_active' => $request->is_active,
            ]);
        }
        if($slider) {
            //return success with Api Resource
            return new SliderResource(true, 'Data Slider Berhasil Diupdate!', $slider);
        }
        //return failed with Api Resource
        return new SliderResource(false, 'Data Slider Gagal Diupdate!', null);
    }

    public function changeActiveSlider(Request $request, $id)
    {
        // dd($request->status);
        $slider      = Slider::findOrFail($id);
        $slider->is_active = $request->is_active;
        $slider->save();

        if($slider) {
            //return success with Api Resource
            return new SliderResource(true, 'Data Slider Berhasil!', $slider);
        }
        //return failed with Api Resource
        return new SliderResource(false, 'Data Slider Gagal Diupdate!', $slider);
    }

    /**
     * Remove the specified resource from storage.
     *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(int $id)
    {
        $slider = Slider::find($id);
        //remove image
        // Storage::disk('local')->delete('public/products/'.basename($product->image));
        if($slider->delete()) {
            //return success with Api Resource
            return new SliderResource(true, 'Data Slider Berhasil Dihapus!', null);
        }
        //return failed with Api Resource
        return new SliderResource(false, 'Data Slider Gagal Dihapus!', null);
    }
    
}
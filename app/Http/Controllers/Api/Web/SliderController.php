<?php
namespace App\Http\Controllers\Api\Web;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SliderResource;
use App\Models\Categories;
use App\Models\Slider;

class SliderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get categories
        $slider = Slider::where('is_active', 1)->latest()->get();
        //return with Api Resource
        return new SliderResource(true, 'List Data Slider', $slider);
    }

}
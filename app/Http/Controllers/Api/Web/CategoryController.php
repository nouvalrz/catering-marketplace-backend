<?php
namespace App\Http\Controllers\Api\Web;
use App\Models\Category;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use App\Http\Resources\CategoryResource;
use App\Models\Categories;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //get categories
        $categories = Categories::latest()->get();
        //return with Api Resource
        return new CategoriesResource(true, 'List Data Categories', $categories);
    }

    /**
    * Display the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function show($slug)
    {
        $category = Categories::with('products.category')
        //get count review and average review
        ->with('products', function ($query) {
        $query->withCount('reviews');
        $query->withAvg('reviews', 'rating');
        })
        ->where('slug', $slug)->first();
        if($category) {
            //return success with Api Resource
            return new CategoriesResource(true, 'Data Product By Category : '.$category->name.'', $category);
        }
        //return failed with Api Resource
        return new CategoriesResource(false, 'Detail Data Category Tidak Ditemukan!', null);
    }
}
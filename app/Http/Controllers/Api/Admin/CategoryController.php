<?php

namespace App\Http\Controllers\Api\Admin;

use App\Models\Categories;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoriesResource;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\CateringResource;
use Illuminate\Support\Facades\Validator;


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
        $categories = Categories::where('type', 'like', '%'. request()->type . '%');
        $categories = $categories->when(request()->q, function($categories)
        {
            $categories = $categories->where('name', 'like', '%'. request()->q . '%');
        })->latest()->paginate(request()->pages);
        //return with Api Resource
        return new CategoriesResource(true, 'List Data Categories', $categories);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            // 'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'name' => 'required|unique:categories',
            'type' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //upload image
        // $image = $request->file('image');
        // $image->storeAs('public/categories', $image->hashName());
        //create category
        $category = Categories::create([
            // 'image'=> $image->hashName(),
            'name' => $request->name,
            'type' => $request->type,
            // 'slug' => Str::slug($request->name, '-'),
        ]);
        if($category) {
            //return success with Api Resource
            return new CategoriesResource(true, 'Data Category Berhasil Disimpan!', $category);
        }
        //return failed with Api Resource
        return new CategoriesResource(false, 'Data Category Gagal Disimpan!', null);
    }
    /**
    * Display the specified resource.
    *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $category = Categories::whereId($id)->first();
        if($category) {
            //return success with Api Resource
            return new CategoriesResource(true, 'Detail Data Category!', $category);
        }
        //return failed with Api Resource
        return new CategoriesResource(false, 'Detail Data Category Tidak Ditemukan!', null);
    }
    /**
    * Update the specified resource in storage.
    *
    * @param \Illuminate\Http\Request $request
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, Categories $category)
    {
        $validator = Validator::make($request->all(), [
            'name' =>'required|unique:categories,name,'.$category->id,
            'type' =>'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //update category without image
        $category->update([
            'name' => $request->name,
            'type' => $request->type,
            // 'slug' => Str::slug($request->name, '-'),
        ]);
        if($category) {
            //return success with Api Resource
            return new CategoriesResource(true, 'Data Category Berhasil Diupdate!', $category);
        }
        //return failed with Api Resource
        return new CategoriesResource(false, 'Data Category Gagal Diupdate!', null);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categories $category)
    {
        //remove image
        // Storage::disk('local')->delete('public/categories/'.basename($category->
        // image));
        if($category->delete()) {
            //return success with Api Resource
            return new CategoriesResource(true, 'Data Category Berhasil Dihapus!', null);
        }
        //return failed with Api Resource
        return new CategoriesResource(false, 'Data Category Gagal Dihapus!', null);
    }
}

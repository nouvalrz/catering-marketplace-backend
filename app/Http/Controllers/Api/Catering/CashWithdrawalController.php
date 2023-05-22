<?php

namespace App\Http\Controllers\Api\Catering;

use App\Http\Controllers\Controller;
use App\Http\Resources\CashWithdrawalResource;
use App\Http\Resources\DiscountResource;
use App\Models\CashWithdrawal;
use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Carbon;


class CashWithdrawalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        
        // $now = Carbon::today()->format('Y-m-d')->toDateString();
        $now = Carbon::today()->toDateString();
        $active = request()->active;

        // if($active){
        //     if($active == 'Aktif'){
        //         $discounts = Discount::where('catering_id', '=', $cateringId)->whereDate('end_date', '>=', $now)->when(request()->q,
        //         function($discounts) {
        //             $discounts = $discounts->where('title', 'like', '%'. request()->q . '%');
        //         })->latest()->paginate(request()->pages);
        //         //return with Api Resource
        //         return new DiscountResource(true, 'List Data Discount', $discounts);
        //     }else{
        //         $discounts = Discount::where('catering_id', '=', $cateringId)->whereDate('end_date', '<', $now)->when(request()->q,
        //         function($discounts) {
        //             $discounts = $discounts->where('title', 'like', '%'. request()->q . '%');
        //         })->latest()->paginate(request()->pages);
        //         //return with Api Resource
        //         return new DiscountResource(true, 'List Data Discount', $discounts);
        //     };
        // };
        $cashWithdrawal = CashWithdrawal::where('approved', 'like', '%'. request()->status . '%');
        $cashWithdrawal = $cashWithdrawal->where('role', 'catering')->where('catering_id', $cateringId)->when(request()->q,
        function($cashWithdrawal) {
            $cashWithdrawal = $cashWithdrawal->where('account_name', 'like', '%'. request()->q . '%');
        })->latest()->paginate(request()->pages);
        //return with Api Resource
        if($cashWithdrawal){
            return new CashWithdrawalResource(true, 'List Data Pencairan', $cashWithdrawal);
        }
        return new CashWithdrawalResource(false, 'List Data Pencairan Tidak Ada', null);

        //get products
        // $products = Product::with('category')->when(request()->q,
        // $discounts = $discounts::where('catering_id', $cateringId)->when(request()->q,
        // function($discounts) {
        //     $discounts = $discounts->where('title', 'like', '%'. request()->q . '%');
        // })->latest()->paginate(request()->pages);
        // //return with Api Resource
        // return new DiscountResource(true, 'List Data Discount', $discounts);
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        $validator = Validator::make($request->all(), [
            // 'image' => 'required|image|mimes:jpeg,jpg,png|max:2000',
            'bank_name' => 'required',
            'account_name' => 'required',
            'bank_account' => 'required',
            'nominal' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //upload image
        // $image = $request->file('image');
        // $image->storeAs('public/products', $image->hashName());
        //create product
        $cashWithdrawal = CashWithdrawal::create([
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            // 'slug' => Str::slug($request->title, '-'),
            'catering_id' => $cateringId,
            'bank_account' => $request->bank_account,
            'nominal' => $request->nominal,
            'approved' => 'pending',
            'role' => 'catering',

        ]);
        if($cashWithdrawal) {
            //return success with Api Resource
            return new CashWithdrawalResource(true, 'Data Cash Withdrawal Berhasil Disimpan!', $cashWithdrawal);
        }
        //return failed with Api Resource
        return new CashWithdrawalResource(false, 'Data Cash Withdrawal Gagal Disimpan!', null);
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
        
        $cashWithdrawal = CashWithdrawal::whereId($id)->first();
        if($cashWithdrawal) {
            //return success with Api Resource
            return new CashWithdrawalResource(true, 'Detail Data Withdrawal!', $cashWithdrawal);
        }
        //return failed with Api Resource
        return new CashWithdrawalResource(false, 'Detail Data Withdrawal Tidak Ditemukan!', null);
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        $cashWithdrawal = CashWithdrawal::find($id);
        // dd($discounts);
        $userId = auth()->guard('api_catering')->user()->id;
        $cateringId = DB::table('caterings')->where('user_id', $userId)->value('id');
        
        $validator = Validator::make($request->all(), [
            'bank_name' => 'required',
            'account_name' => 'required',
            'bank_account' => 'required',
            'nominal' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
        //check image update


        $cashWithdrawal->update([
            'bank_name' => $request->bank_name,
            'account_name' => $request->account_name,
            // 'slug' => Str::slug($request->title, '-'),
            'catering_id' => $cateringId,
            'bank_account' => $request->bank_account,
            'nominal' => $request->nominal,
            'approved' => 'pending',
            'role' => 'catering',

        ]);
        // dd($discounts);
        if($cashWithdrawal) {
            //return success with Api Resource
            return new CashWithdrawalResource(true, 'Data Withdrawal Berhasil Diupdate!', $cashWithdrawal);
        }
        //return failed with Api Resource
        return new CashWithdrawalResource(false, 'Data Withdrawal Gagal Diupdate!', null);
    }
    
    /**
     * Remove the specified resource from storage.
     *
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function destroy(int $id)
    {
        $discounts = Discount::find($id);
        //remove image
        // Storage::disk('local')->delete('public/products/'.basename($product->image));
        if($discounts->delete()) {
            //return success with Api Resource
            return new DiscountResource(true, 'Data Discount Berhasil Dihapus!', null);
        }
        //return failed with Api Resource
        return new DiscountResource(false, 'Data Discount Gagal Dihapus!', null);
    }
}

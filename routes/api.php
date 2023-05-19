<?php

use App\Http\Controllers\Api\Admin\AdminAuthController;
use App\Http\Controllers\Api\Catering\CategoryController;
use App\Http\Controllers\Api\Catering\ProductController;
use App\Http\Controllers\Api\Catering\CateringAuthController;
use App\Http\Controllers\Api\Catering\CateringRegisterController;
use App\Http\Controllers\Api\Catering\ChatController;
use App\Http\Controllers\Api\Catering\ComplaintController;
use App\Http\Controllers\Api\Catering\DeliveryCostsController;
use App\Http\Controllers\Api\Catering\DeliveryCoveragesController;
use App\Http\Controllers\Api\Catering\DiscountController;
use App\Http\Controllers\Api\Catering\OrdersController;
use App\Http\Controllers\Api\Catering\ProfileController;
use App\Http\Controllers\Api\Catering\DashboardController;
use App\Http\Controllers\Api\Catering\ReviewController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\CustomerRegisterController;
use App\Http\Controllers\CateringController;
use App\Http\Controllers\CustomerController;
use App\Models\DeliveryCost;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Web\ProvinceController;
use App\Http\Controllers\Api\Web\RegencyController;
use App\Http\Controllers\Api\Web\DistrictController;
use App\Http\Controllers\Api\Web\VillageController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('customer/register', [CustomerRegisterController::class, 'create']);
Route::post('customer/register/validate-otp', [CustomerRegisterController::class, 'validateOtp']);
Route::post('customer/register/check-email', [CustomerRegisterController::class, 'checkEmailAvail']);
Route::post('customer/register/check-phone', [CustomerRegisterController::class, 'checkPhoneAvail']);
Route::post('customer/login', [CustomerAuthController::class, 'login']);
Route::post('customer/logout', [CustomerAuthController::class, 'logout']);


Route::get('customer/profile', [CustomerController::class, 'profile']);
Route::post('customer/profile/edit', [CustomerController::class, 'update']);


Route::post('catering/profile/upload-image', [CateringController::class, 'uploadImage']);
Route::post('catering/profile/add-product', [CateringController::class, 'addProduct']);
Route::post('catering/client/get-relevant', [\App\Http\Controllers\CateringToClientController::class, 'getRelevantCatering']);

//tambah
// Auth::routes(['verify' => true]);

Route::prefix('admin')->group(function(){
    //route register
    Route::post('/register', [AdminAuthController::class, 'regist'], ['as' => 'admin']);
    //route login
    Route::post('/login', [AdminAuthController::class, 'index', ['as' => 'admin']]);

    //group route with middleware "auth:api_admin"
    Route::group(['middleware' => 'auth:api_admin'], function() {
        //data user
        Route::get('/user', [AdminAuthController::class, 'getUser', ['as' => 'admin']]);
        //refresh token JWT
        Route::get('/refresh', [AdminAuthController::class, 'refreshToken', ['as' => 'admin']]);
        //logout
        Route::post('/logout', [AdminAuthController::class, 'logout', ['as' => 'admin']]);
        //catering
        Route::apiResource('/caterings', App\Http\Controllers\Api\Admin\CateringController::class, ['except' => ['create', 'edit', 'destroy'], 'as' => 'admin']);
        
        Route::post('/cateringsA/{id}', 'App\Http\Controllers\Api\Admin\CateringController@changeVerifiedCatering');
        
        Route::apiResource('/dashboard', App\Http\Controllers\Api\Admin\DashboardController::class, ['except' => ['create', 'edit', 'destroy'], 'as' => 'admin']);
        
        Route::apiResource('/customers', App\Http\Controllers\Api\Admin\CustomerController::class, ['except' => ['create', 'edit', 'destroy'], 'as' => 'admin']);
        
        Route::apiResource('/discounts', App\Http\Controllers\Api\Admin\DiscountController::class, ['except' => ['create', 'edit'], 'as' => 'admin']);
    });
   
});

Route::prefix('catering')->group(function($router){
    //route register
    Route::post('/register', [CateringRegisterController::class, 'store'], ['as' => 'catering']);
    // validate otp
    Route::post('/validate-otp', [CateringRegisterController::class, 'validateOtp'], ['as' => 'catering']);
    //route login
    Route::post('/login', [CateringAuthController::class, 'index', ['as' => 'catering']]);
    //route get otp
    Route::post('/get-otp', [CateringAuthController::class, 'getOtp', ['as' => 'catering']]);
    
    Route::post('/check-email', [CateringAuthController::class, 'checkEmail', ['as' => 'catering']]);
    
    Route::post('/check-otp', [CateringAuthController::class, 'checkOtp', ['as' => 'catering']]);
    
    Route::post('/change-password', [CateringAuthController::class, 'changePassword', ['as' => 'catering']]);
    
    Route::post('/check-user', [CateringAuthController::class, 'checkUser', ['as' => 'catering']]);
    
    Route::post('/change-email-check', [CateringAuthController::class, 'changeEmailCheck', ['as' => 'catering']]);

    //group route with middleware "auth:api_catering"
    Route::group(['middleware' => 'auth:api_catering'], function($router) {
        //data user
        Route::get('/user', [CateringAuthController::class, 'getUser', ['as' => 'catering']]);
        //refresh token JWT
        Route::get('/refresh', [CateringAuthController::class, 'refreshToken', ['as' => 'catering']]);
        //logout
        Route::post('/logout', [CateringAuthController::class, 'logout', ['as' => 'catering']]);
        //dashboard
        Route::get('/dashboard', [DashboardController::class, 'index', ['as' => 'catering']]);
        //categories resource
        Route::apiResource('/categories', CategoryController::class, ['except' => ['create', 'edit'], 'as' => 'catering']);
        //products resource
        Route::apiResource('/products', ProductController::class, ['except' => ['create', 'edit'], 'as' => 'catering']);
        
        Route::post('/products/updateOptionItem', 'App\Http\Controllers\Api\Catering\ProductController@updateOptionItem');
        
        Route::delete('/products/deleteOption/{id}', 'App\Http\Controllers\Api\Catering\ProductController@deleteOption');

        Route::delete('/products/deleteItem/{id}', 'App\Http\Controllers\Api\Catering\ProductController@deleteItem');
        //products change available
        Route::post('/productsA/{id}', 'App\Http\Controllers\Api\Catering\ProductController@changeAvailableProduct');
        //discount resource
        Route::apiResource('/discounts', DiscountController::class, ['except' => ['create', 'edit'], 'as' => 'catering']);
        //review resource
        Route::apiResource('/reviews', ReviewController::class, ['except' => ['create', 'edit'], 'as' => 'catering']);
        //delivery cost resource
        Route::apiResource('/deliverycost', DeliveryCostsController::class, ['except' => ['create', 'edit'], 'as' => 'catering']);
        //delivery coverage resource
        Route::apiResource('/deliverycoverage', DeliveryCoveragesController::class, ['except' => ['create', 'edit'], 'as' => 'catering']);
        
        Route::apiResource('/profile', ProfileController::class, ['except' => ['create', 'edit'], 'as' => 'catering']);

        Route::apiResource('/order', OrdersController::class, ['except' => ['create', 'edit'], 'as' => 'catering']);
        //products change status order
        Route::get('/order/listProduct/{id}', 'App\Http\Controllers\Api\Catering\OrdersController@listProducts');

        Route::post('/orderA/{id}', 'App\Http\Controllers\Api\Catering\OrdersController@changeStatus');
        
        Route::apiResource('/complaint', ComplaintController::class, ['except' => ['create', 'edit'], 'as' => 'catering']);
        
        Route::post('/message', 'App\Http\Controllers\Api\Catering\ChatController@message');
        
        Route::get('/roomChats', 'App\Http\Controllers\Api\Catering\ChatController@roomChats');
        
        Route::get('/chats/{id}', 'App\Http\Controllers\Api\Catering\ChatController@chats');
        
        Route::post('/chats/send', 'App\Http\Controllers\Api\Catering\ChatController@sendMessage');
    });
   
});

//group route with prefix "web"
Route::prefix('web')->group(function () {
    //categories resource
    // Route::apiResource('/categories', App\Http\Controllers\Api\Web\CategoryController::class, ['except' =>
    // ['create', 'store', 'edit', 'update', 'destroy'], 'as' => 'web']);

    Route::get('/categories/product', 'App\Http\Controllers\Api\Web\CategoryController@categoriesProduct');

    Route::get('/categories/catering', 'App\Http\Controllers\Api\Web\CategoryController@categoriesCatering');

    //province resource
    Route::apiResource('/province', App\Http\Controllers\Api\Web\ProvinceController::class, ['except' =>
    ['create', 'store', 'edit', 'update', 'destroy'], 'as' => 'web']);
    //regency resource
    Route::apiResource('/regency', App\Http\Controllers\Api\Web\RegencyController::class, ['except' =>
    ['create', 'store', 'edit', 'update', 'destroy'], 'as' => 'web']);
    //district resource
    Route::apiResource('/district', App\Http\Controllers\Api\Web\DistrictController::class, ['except' =>
    ['create', 'store', 'edit', 'update', 'destroy'], 'as' => 'web']);
    //district resource
    Route::apiResource('/village', App\Http\Controllers\Api\Web\VillagesController::class, ['except' =>
    ['create', 'store', 'edit', 'update', 'destroy'], 'as' => 'web']);
});


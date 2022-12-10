<?php

use App\Http\Controllers\Api\Catering\CategoryController;
use App\Http\Controllers\Api\Catering\ProductController;
use App\Http\Controllers\Api\Catering\CateringAuthController;
use App\Http\Controllers\Api\Catering\CateringRegisterController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\CustomerRegisterController;
use App\Http\Controllers\CateringController;
use App\Http\Controllers\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::prefix('catering')->group(function(){
    //route register
    Route::post('/register', [CateringRegisterController::class, 'store'], ['as' => 'catering']);
    //route login
    Route::post('/login', [CateringAuthController::class, 'index', ['as' => 'catering']]);

    //group route with middleware "auth:api_catering"
    Route::group(['middleware' => 'auth:api_catering'], function() {
        //data user
        Route::get('/user', [CateringAuthController::class, 'getUser', ['as' => 'catering']]);
        //refresh token JWT
        Route::get('/refresh', [CateringAuthController::class, 'refreshToken', ['as' => 'catering']]);
        //logout
        Route::post('/logout', [CateringAuthController::class, 'logout', ['as' => 'catering']]);
        //dashboard
        // Route::get('/dashboard', [DashboardController::class, 'index', ['as' => 'catering']]);
        //categories resource
        Route::apiResource('/categories', CategoryController::class, ['except' => ['create', 'edit'], 'as' => 'catering']);
        //products resource
        Route::apiResource('/products', ProductController::class, ['except' => ['create', 'edit'], 'as' => 'catering']);
    });
   
});
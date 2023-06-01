<?php

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\CustomerRegisterController;
use App\Http\Controllers\CateringController;
use App\Http\Controllers\CateringToClientController;
use App\Http\Controllers\CustomerAddressController;
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
Route::get('customer/logout', [CustomerAuthController::class, 'logout']);


Route::get('customer/profile', [CustomerController::class, 'profile']);
Route::post('customer/profile/edit', [CustomerController::class, 'update']);

Route::get('customer/address', [CustomerAddressController::class, 'getAllAddresses']);
Route::post('customer/address/add', [CustomerAddressController::class, 'addAddress']);


Route::post('customer/cart/add-instant', [\App\Http\Controllers\CustomerCartController::class, 'createInstant']);
Route::post('customer/cart/preorder/add', [\App\Http\Controllers\CustomerCartController::class, 'createPreOrderCart']);
Route::get('customer/cart/preorder/{id}/delete', [\App\Http\Controllers\CustomerCartController::class, 'destroy']);
Route::get('customer/cart/index', [\App\Http\Controllers\CustomerCartController::class, 'index']);


//Route::get('customer/cart/index', [\App\Http\Controllers\CustomerCartController::class, 'getAllCart']);
Route::post('customer/preorder/create', [\App\Http\Controllers\CustomerOrderController::class, 'createPreOrder']);
Route::post('customer/preorder/receive', [\App\Http\Controllers\CustomerOrderController::class, 'receive']);
Route::get('customer/order/index', [\App\Http\Controllers\CustomerOrderController::class, 'index']);
Route::get('customer/order/{id}/show', [\App\Http\Controllers\CustomerOrderController::class, 'show']);
Route::get('customer/order/{id}/show-paid-status', [\App\Http\Controllers\CustomerOrderController::class, 'getOrderPaidStatus']);
Route::get('customer/order/{id}/set-to-accepted', [\App\Http\Controllers\CustomerOrderController::class, 'setOrderToAccepted']);

Route::post('customer/subsorder/create', [\App\Http\Controllers\CustomerOrderController::class, 'createSubsOrder']);

// Chat Route
Route::post('customer/chat/show', [\App\Http\Controllers\ChatController::class, 'showForCustomer']);
Route::get('customer/chat/index', [\App\Http\Controllers\ChatController::class, 'indexForCustomer']);
Route::post('customer/chat/send', [\App\Http\Controllers\ChatController::class, 'sendForCustomer']);

Route::post('customer/review/create', [\App\Http\Controllers\ReviewController::class, 'create']);
Route::get('catering/client/{id}/reviews', [\App\Http\Controllers\CateringToClientController::class, 'getCateringReviews']);

Route::post('catering/profile/upload-image', [CateringController::class, 'uploadImage']);
Route::post('catering/product/get', [\App\Http\Controllers\ProductToClientController::class, 'getProductDetail']);
Route::post('catering/profile/add-product', [CateringController::class, 'addProduct']);
Route::post('catering/client/get-relevant', [\App\Http\Controllers\CateringToClientController::class, 'getRelevantCatering']);
Route::post('catering/client/get-search-result', [\App\Http\Controllers\CateringToClientController::class, 'getSearchResultCatering']);
Route::post('catering/client/get-category-result', [\App\Http\Controllers\CateringToClientController::class, 'getCategoryResultCatering']);
Route::get('catering/client/{id}/get-products', [CateringToClientController::class, 'getProductsFromCatering']);
Route::get('catering/client/{id}/get-delivery-time-range', [CateringToClientController::class, 'getCateringDeliveryTimeRange']);
Route::get('catering/client/{id}/get-catering-work-day', [CateringToClientController::class, 'getCateringWorkDay']);
Route::get('catering/client/{id}/get-discounts', [CateringToClientController::class, 'getCateringDiscounts']);

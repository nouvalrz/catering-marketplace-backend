<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return "Ini halaman utama";
});

Route::get('/inibeda', function () {
    return "Ini halaman utama";
});

Route::get('/customer/preorder/showsnaptoken/{id}', [\App\Http\Controllers\CustomerOrderController::class, 'showSnapToken']);

Route::get('/customer/payment/back', function (){
  return "back";
});

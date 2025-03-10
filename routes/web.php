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

    return view('welcome');
});

Auth::routes();

Route::group(['middleware' => 'auth'], function() {

//ログイン機能
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
//商品一覧画面
Route::get('/productlist', [App\Http\Controllers\ProductController::class, 'show'])->name('productlist');
//登録処理画面
Route::post('/productstore', [App\Http\Controllers\ProductController::class, 'store'])->name('productstore');
//新規登録画面
Route::get('/productcreate', [App\Http\Controllers\ProductController::class, 'create'])->name('productcreate');
//商品詳細画面
Route::get('/productdetail/{id}', [App\Http\Controllers\ProductController::class, 'detail'])->name('productdetail');
//商品編集画面
Route::get('/productedit/{id}', [App\Http\Controllers\ProductController::class, 'edit'])->name('productedit');
//商品編集更新処理
Route::put('/productupdate/{id}', [App\Http\Controllers\ProductController::class, 'update'])->name('productupdate');
//削除機能
Route::delete('/products/{id}', [App\Http\Controllers\ProductController::class, 'destroy'])->name('productdestroy');

//Ajax検索機能
Route::get('/productsearch', [App\Http\Controllers\ProductController::class, 'productsearch'])->name('productsearch');

});
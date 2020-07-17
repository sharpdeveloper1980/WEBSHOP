<?php

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


Route::prefix('embed')->group(function () {
    Route::get('/products', 'ProductController@showAllRendered');
    Route::get('/product/{id}', 'ProductController@getProductByIdRendered');
    Route::get('/top-bar', 'ProductController@renderTopBar');
    Route::get('/categories', 'CategoryController@showAllRendered');
    Route::get('/category/{category_uid}', 'CategoryController@getProductsByCategoryUidRendered');
    Route::get('/seller/{id}', 'ClientController@getClientByIdRendered');
    Route::get('/store/{slug}', 'StoreController@getStoreBySlugRendered');
});


Auth::routes();

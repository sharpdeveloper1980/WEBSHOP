<?php

use Illuminate\Http\Request;

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

//Route::middleware('auth:api')->get('/user', function (Request $request) {
//    return $request->user();
//});

Route::post('/login', 'AuthController@login');
Route::post('/register', 'AuthController@register');
Route::post('/logout', 'AuthController@logout');
Route::get('/products', 'ProductController@showAll');
Route::get('/product/{id}', 'ProductController@getProductById');
Route::get('/store/{slug}', 'StoreController@getStoreBySlug');
Route::get('/store/{slug}/products', 'StoreController@getStoreProducts');
Route::get('/seller/{id}', 'UserController@getUserById');
Route::get('/categories', 'CategoryController@showAll');
Route::get('/category/{category_uid}', 'CategoryController@getProductsByCategoryUid');

Route::get('/sync_stores', 'DataSyncController@init');


Route::middleware(['jwt.auth'])->group(function () {
//    Route::get('/test', 'AuthController@test');
    Route::get('/me', 'AuthController@me');
});


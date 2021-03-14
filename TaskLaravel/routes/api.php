<?php

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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->middleware('cors')->namespace('API')->group(function () {
	Route::post('/register','AuthControllerr@Register');
	Route::post('/login','AuthControllerr@Login');
	Route::delete('/logout/{token}','AuthControllerr@Logout');
	Route::post('/updateProfile/{id}','AuthControllerr@updateProfile');
	Route::post('/addProduct','ProductController@addProduct');
	Route::delete('/deleteProduct/{id}','ProductController@deleteProduct');
	Route::get('/getProduct','ProductController@getProduct');
	Route::post('/updateProduct/{id}','ProductController@updateProduct');
	Route::get('/SearchBrand/{brand}','ProductController@SearchBYBrand');
});
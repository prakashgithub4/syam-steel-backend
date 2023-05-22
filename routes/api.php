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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
header('Access-Control-Allow-Methods: GET, POST, PATCH, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Origin, Content-Type, X-Auth-Token, Authorization, Accept,charset,boundary,Content-Length');
header('Access-Control-Allow-Origin: *');
Route::group(['api','cors'],function(){
    Route::get('/','JsonController@index');
//Route::get('add/','JsonController@add');
Route::post('/store','JsonController@save');
Route::get('/delete/{id}','JsonController@remove');
Route::get('/edit/{id}','JsonController@edit');
Route::get('/details/{id}','JsonController@details');
Route::post('update/{id}','JsonController@update');

});


<?php

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

Route::get('affiliates', 'AffiliateController@index');
Route::post('affiliates/file', 'AffiliateController@storeFile');
Route::get('affiliates/{affiliate}', 'AffiliateController@show');
Route::put('affiliates/{affiliate}', 'AffiliateController@update');
Route::delete('affiliates/{affiliate}', 'AffiliateController@destroy');
Route::get('affiliates-dublin-proximity', 'AffiliateController@proximity');

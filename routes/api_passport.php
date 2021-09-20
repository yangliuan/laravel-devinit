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

Route::post('mobile-login', 'AuthController@mobile');//手机号登录注册
Route::post('miniprogram-login', 'AuthController@miniprogram');//小程序登录注册
Route::post('logout', 'AuthController@logout')->middleware(['auth:api', 'scope:api']);//登出

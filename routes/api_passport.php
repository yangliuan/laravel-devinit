<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;

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

Route::post('mobile-login', [AuthController::class,'mobile']);//手机号登录注册
Route::post('miniprogram-login', [AuthController::class,'miniprogram']);//小程序登录注册
Route::post('logout', [AuthController::class,'logout'])->middleware(['auth:api', 'scope:api']);//登出

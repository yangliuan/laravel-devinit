<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Common\NotifyController;

/*
|--------------------------------------------------------------------------
| Common Routes
|--------------------------------------------------------------------------
|
| Here is where you can register Common routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "Common" middleware group. Now create something great!
|
*/

Route::post('sendsms', [NotifyController::class,'sendSms']); //发短信

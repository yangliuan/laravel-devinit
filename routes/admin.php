<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN Routes
|--------------------------------------------------------------------------
|
| Here is where you can register ADMIN routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "admin" middleware group. Enjoy building your API!
|
*/

Route::post('login', 'AdminController@login'); //登录
Route::post('logout', 'AdminController@logout'); //登出
Route::get('syslogs', 'AdminController@syslogs'); //系统日志

Route::group(['prefix' => 'admin'], function ()
{
    Route::match(['put', 'patch'], 'status/{id}', 'AdminController@status'); //启用/禁用
});

Route::group(['prefix' => 'group'], function ()
{
    Route::get('rules', 'GroupController@rules'); //所有权限
    Route::get('setting/{id}', 'GroupController@setting'); //获取组所有权限
    Route::match(['put', 'patch'], 'set/{id}', 'GroupController@set'); //设置组的权限
});

Route::apiResources([
    'admin' => 'AdminController',
    'group' => 'GroupController',
]);

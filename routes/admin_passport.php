<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\GroupController;
use App\Http\Controllers\Admin\RulesController;

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
Route::post('login', [AdminController::class,'login']); //登录

Route::middleware(['auth:admin', 'scope:admin'])->group(function () {
    Route::post('logout', [AdminController::class,'logout']);//登出
    Route::get('syslogs', [AdminController::class,'syslogs']);//系统日志
    Route::get('info', [AdminController::class,'info']);//管理员登录信息和权限
    Route::get('sysinfo', [AdminController::class,'sysInfo']);//系统信息

    Route::group(['prefix' => 'admin'], function () {
        Route::match(['put', 'patch'], 'status/{id}', [AdminController::class,'status']);//管理员 启用/禁用
    });

    Route::group(['prefix' => 'group'], function () {
        Route::match(['put', 'patch'], 'status/{id}', [GroupController::class,'status']);//管理组 启用/禁用
        Route::get('select-menus', [GroupController::class,'selectMenus']);//管理组选择菜单
        Route::get('rules', [GroupController::class,'rules']);//所有权限
        Route::get('setting/{id}', [GroupController::class,'setting']);//获取组所有权限
        Route::match(['put', 'patch'], 'set/{id}', [GroupController::class,'set']);//设置组的权限
    });

    Route::group(['prefix' => 'rules'], function () {
        Route::match(['put', 'patch'], 'status/{id}', [RulesController::class,'status']);//权限规则 启用/禁用
        Route::match(['put', 'patch'], 'log-status/{id}', [RulesController::class,'logStatus']);//权限规则 日志 启用/禁用
        Route::get('select-menus', [RulesController::class,'selectMenus']);//权限规则 上级菜单
    });

    Route::apiResources([
        'admin' => AdminController::class,//管理员
        'group' => GroupController::class,//管理组
        'rules' => RulesController::class,//权限规则
    ]);
});

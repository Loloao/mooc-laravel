<?php

use App\Http\Controllers\Wx\AuthController;
use Illuminate\Support\Facades\Route;

Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/regCaptcha', [AuthController::class, 'regCaptcha']);
Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('auth/info', [AuthController::class, 'info']);
Route::post('auth/logout', [AuthController::class, 'logout']);
Route::post('auth/reset', [AuthController::class, 'reset']);
Route::post('auth/captcha', [AuthController::class, 'regCaptcha']);
Route::post('auth/profile', [AuthController::class, 'profile']);

#用户模块-地址
Route::get('address/list', 'AddressController@list');
Route::post('address/delete', 'AddressController@delete');
Route::get('address/detail', 'AddressController@detail');
Route::post('address/save', 'AddressController@save');

# 商品模块-类目
Route::get("catalog/index", 'CatalogController@index'); // 分类目录全部分类数据接口
Route::get('catalog/current', 'CatalogController@current'); // 分类目录当前分类数据接口
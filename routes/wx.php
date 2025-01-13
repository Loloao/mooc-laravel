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

#用户模块
Route::get('address/list', 'AddressController@list');
Route::get('address/detail', 'AddressController@detail');
Route::post('address/save', 'AddressController@save');
Route::post('address/delete', 'AddressController@delete');
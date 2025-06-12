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
Route::get("catalog/index", 'CatalogController@index');     // 分类目录全部分类数据接口
Route::get('catalog/current', 'CatalogController@current'); // 分类目录当前分类数据接口

# 商品模块-品牌
Route::get("brand/list", 'BrandController@list');     // 品牌列表
Route::get("brand/detail", 'BrandController@detail'); // 品牌详情

# 商品模块-商品
Route::get("goods/count", 'GoodsController@count');       // 统计商品总数
Route::get("goods/category", 'GoodsController@category'); // 根据分类获取商品列表数据
Route::get("goods/list", 'GoodsController@list');         // 获取商品列表
Route::get("goods/detail", 'GoodsController@detail');     // 获得商品详情

# 优惠券
Route::get("coupon/list", 'CouponController@list');        // 优惠券列表
Route::get("coupon/myList", 'CouponController@myList');    // 我的优惠券列表
Route::post("coupon/receive", 'CouponController@receive'); // 优惠券领取
// Route::get("coupon/selectList", ''); // 当前订单可用优惠券列表

# 团购
Route::get('groupon/list', 'GrouponController@list');
Route::get('groupon/test', 'GrouponController@test');

# 购物车
Route::post('cart/add', 'CartController@add');              // 添加商品到购物车
Route::get('cart/goodsCount', 'CartController@goodsCount'); // 获取购物车商品件数
Route::post('cart/update', 'CartController@update');        // 更新购物车商品
Route::post('cart/delete', 'CartController@delete');        // 删除购物车商品
Route::post('cart/checked', 'CartController@checked');      // 选择或取消商品
Route::post('cart/fastAdd', 'CartController@fastAdd');      // 选择或取消商品
Route::get('cart/index', 'CartController@index');           // 获取购物车的数据
Route::get('cart/checkout', 'CartController@checkout');     // 下单前信息确认

# 订单
Route::any('order/submit', 'OrderController@submit'); // 提交订单

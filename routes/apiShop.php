<?php

use Illuminate\Http\Request;

Route::any('/', function(){
    abort(404);
});

//取Menu
Route::post('/GetMenu', 'getMenu@index');

//取Menu明細
Route::post('/GetMenuCommodity', 'getMenuCommodity@index');

//取商品明細
Route::post('/GetShopltemDetail', 'getShopltemDetail@index');

//新增會員訂單
Route::post('/CommodityOrderAdd', 'commodityOrderAdd@index');

//修改會員訂單
Route::post('/CommodityOrderUpdate', 'commodityOrderUpdate@index');

//取購物車商品
Route::post('/GetShopltemCar', 'getShopltemCar@index');

//簡易會員資料
Route::post('/DetailSimple', 'detailSimple@index');
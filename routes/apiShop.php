<?php

use Illuminate\Http\Request;

Route::any('/', function(){
    abort(404);
});

//新增商品
Route::post('/ShopltemAdd', 'shopltemAdd@index');

//取Menu
Route::post('/GetMenu', 'getMenu@index');

//取Menu明細
Route::post('/GetMenuCommodity', 'getMenuCommodity@index');

//取商品明細
Route::post('/GetShopltemDetail', 'getShopltemDetail@index');
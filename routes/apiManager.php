<?php

//登入
Route::post('/Login', 'login@index');

//取得控制項
Route::post('/Ctrl', 'ctrl@index');

//選單
Route::post('/GetMenu', 'getMenu@index');

//取得圖片資訊
Route::post('/GetImages', 'images@search');

//上傳圖片資訊
Route::post('/InsertImages', 'images@insert');

//新增商品
Route::post('/AddCommodity', 'commodity@add');

//取商品
Route::post('/GetCommodity', 'commodity@get');

//更新商品
Route::post('/UpdateCommodity', 'commodity@update');

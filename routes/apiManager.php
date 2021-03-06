<?php

/*** 信用卡回傳 ***/
//回傳成功
Route::post('/payCardSuccess', 'payCard\payCard@success');

//回傳失敗
Route::post('/payCardError', 'payCard\payCard@error');
/*** 信用卡回傳 ***/

//登入
Route::post('/Login', 'login@index');

//取得控制項
Route::post('/Ctrl', 'ctrl@index');

//取得麵包屑
Route::post('/TreeUp', 'treeUp@index');

//選單
Route::post('/GetMenu', 'getMenu@index');

//取得會員資料列表
Route::post('/GetAccountList', 'account\accountList@index');

//取得購物報表
Route::post('/GetShopOrderList', 'report\shopOrderList@index');

//取得藏蛋報表
Route::post('/GetRebateList', 'report\rebateList@index');

//取得返利報表
Route::post('/GetBackList', 'report\backList@index');

//取得返利報表
Route::post('/GetTradeList', 'report\tradeList@index');

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

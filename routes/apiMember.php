<?php

use Illuminate\Http\Request;

Route::any('/', function(){
    abort(404);
});

//登入
Route::post('/Login', 'login@index');

//註冊
Route::post('/Create', 'create@index');

//會員資料
Route::post('/Detail', 'detail@index');

//修改會員資料
Route::post('/DetailUpdate', 'detailUpdate@index');

//簡易會員資料
Route::post('/DetailSimple', 'detailSimple@index');

//修改會員密碼
Route::post('/PasswordUpdate', 'passwordUpdate@index');

//設定登入資訊
Route::post('/SetLoginInfo', 'info\setLoginInfo@index');

//新增商品
Route::post('/ShopltemAdd', 'shopltemAdd@index');

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

//驗證驗證碼
Route::post('/VerificationCheck', 'verificationCheck@index');

//驗證驗時效日期
Route::post('/VerificationDate', 'verificationDate@index');

//更新驗證驗時效日期
Route::post('/VerificationDateUpdate', 'verificationDateUpdate@index');

//重發驗證碼
Route::post('/VerificationReSend', 'verificationReSend@index');



// //保持登入
// Route::post('/SetLog', 'info\setLoginInfo@index');


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

//修改會員密碼
Route::post('/PasswordUpdate', 'passwordUpdate@index');

//設定登入資訊
Route::post('/SetLoginInfo', 'info\setLoginInfo@index');

//驗證驗證碼
Route::post('/VerificationCheck', 'verificationCheck@index');
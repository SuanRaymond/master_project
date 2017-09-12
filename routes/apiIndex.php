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

//修改會員照片
Route::post('/PhotoUpdate', 'detailUpdate@photoUpdate');

//設定登入資訊
Route::post('/SetLoginInfo', 'info\setLoginInfo@index');

//驗證驗證碼
Route::post('/VerificationCheck', 'verificationCheck@index');

//驗證驗時效日期
Route::post('/VerificationDate', 'verificationDate@index');

//更新驗證驗時效日期
Route::post('/VerificationDateUpdate', 'verificationDateUpdate@index');

//重發驗證碼
Route::post('/VerificationReSend', 'verificationReSend@index');


//藏蛋返利清單
Route::post('/RebateList', 'rebateAdd@rebateList');

//購買藏蛋返利
Route::post('/RebateAdd', 'rebateAdd@rebateAdd');

//今日任務
Route::post('/GetRebateTaskToday', 'rebateTask@today');

//今日任務清單
Route::post('/GetRebateTaskList', 'rebateTask@list');

//今日簽到
Route::post('/CheckinRebateTask', 'rebateTask@checkin');

//今日刮刮卡
Route::post('/GetRebateTaskScratchCard', 'rebateTask@scratchCard');

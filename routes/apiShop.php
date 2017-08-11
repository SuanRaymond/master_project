<?php

use Illuminate\Http\Request;

Route::any('/', function(){
    abort(404);
});

//取得選單
Route::post('/GetMenu', 'getMenu@index');
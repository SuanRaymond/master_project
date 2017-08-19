<?php

use Illuminate\Http\Request;

Route::any('/', function(){
    abort(404);
});

//取Menu
Route::post('/GetMenu', 'getMenu@index');

//取Menu明細
Route::post('/GetMenuCommodity', 'getMenuCommodity@index');
<?php

namespace App\Http\Controllers\ApiShop;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\shop_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class getMenu extends Controller
{
	//共用參數
    public $system;

    /**
        登入
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得 API 內帳號資料
        5、輸出完整資料
        {"Result":"狀態","Menu":{MenuID":"編號",MenuID":"編號",MenuID":"編號",MenuID":"編號",MenuID":"編號"}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
        $this->system->action = '[judge]';
        $db = with(new shop_repository())->getMenu();

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        $this->system->menu = (object) array();

        foreach ($db as $index => $row) {
            $this->system->menu->$index = (object) array();
            $this->system->menu->$index->MenuID = $row->sMenuID;
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

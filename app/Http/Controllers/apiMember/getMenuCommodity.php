<?php

namespace App\Http\Controllers\ApiMember;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\shop_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class getMenuCommodity extends Controller
{
	//共用參數
    public $system;

    /**
        登入
        1、從對應 API 接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MenuID":"MenuID"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得DB 內帳號資料
        5、輸出完整資料
        {"Result":"狀態","Menu":{"0":{"MenuID":"編號","Title":"標題","SubTitle":"副標","Price":積分,"Points":積分,"Transport":運費},.....}}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
        $this->system->action = '[judge]';

        if($this->system->menuID == '')
            $this->system->menuID = null;

        $db = with(new shop_repository())->getMenuCommodity($this->system->menuID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }


        $this->system->menuCommodity = (object) array();

        foreach($db as $row){
            $menuID = $row->sMenuID;
            $shopID = $row->sShopID;

            if(empty($this->system->menuCommodity->$menuID)){
                $this->system->menuCommodity->$menuID = (object) array();
            }

            $_row = clone $row;
            foreach($_row as $key => $value){
                $tempKey = $key;
                $key = substr($key, 1);
                $row->$key = $value;
                unset($row->$tempKey);
            }

            unset($row->sMenuID);
            unset($row->ShopID);
            $this->system->menuCommodity->$menuID->$shopID = reSetKey($row);
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

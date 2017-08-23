<?php

namespace App\Http\Controllers\ApiShop;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\shop_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class commodityOrderAdd extends Controller
{
	//共用參數
    public $system;

    /**
        登入
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"memberID":"會員編號","phone":"電話","address":"地址","shopID":"商品編號","price":積分,"points":"積分","transport":"運費","quantity":"數量","memo":備註}
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得 API 內帳號資料
        5、輸出完整資料
        {"Result":"狀態","ShoporderID":"訂單編號"}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
        $this->system->action = '[judge]';

        $db = with(new shop_repository())->addMemberCommodityOrder($this->system->memberID, $this->system->phone, $this->system->address, $this->system->shopID, $this->system->price, $this->system->points, $this->system->transport, $this->system->quantity, $this->system->memo);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        $this->system->menuCommodity = (object) array();

        foreach($db as $row){
            $this->system->shoporderID = $row->shoporderID;
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

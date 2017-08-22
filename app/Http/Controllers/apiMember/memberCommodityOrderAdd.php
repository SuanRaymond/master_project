<?php

namespace App\Http\Controllers\ApiMember;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\shop_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class memberCommodityOrderAdd extends Controller
{
	//共用參數
    public $system;

     * @param  int      $_memberID       會員編號
     * @param  string   $_phone          電話
     * @param  string   $_address        地址
     * @param  int      $_shopID         商品編號
     * @param  float    $_price          售價
     * @param  float    $_points         積分
     * @param  float    $_transport      運費
     * @param  int      $_quantity       數量
     * @param  string   $_memo           備註

    /**
        登入
        1、從對應 API 接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"memberID":"會員編號","phone":"電話","address":"地址","shopID":"商品編號","price":積分,"points":"積分","transport":"運費","quantity":"數量","memo":備註}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得DB 內帳號資料
        5、輸出完整資料
        {"Result":"狀態","Menu":{"0":{"MenuID":"編號","Title":"標題","SubTitle":"副標","Price":售價,"Points":積分,"Transport":運費},.....}}
     */

    public function __construct()
    {dd(111);
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
        $this->system->action = '[judge]';


        $db = with(new member_repository())->addMemberCommodityOrder($this->system->memberID, $this->system->phone, $this->system->address, $this->system->shopID, $this->system->price, $this->system->points, $this->system->transport, $this->system->quantity, $this->system->memo);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }
dd($db);

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

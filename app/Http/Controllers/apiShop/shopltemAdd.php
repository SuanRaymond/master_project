<?php

namespace App\Http\Controllers\ApiShop;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\shop_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class shopltemAdd extends Controller
{
	//共用參數
    public $system;

    /**
        登入
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"Title":"標題","SubTitle":"副標","MenuID":"商品類別ID", "Price":"售價", "Points":"積分", "Transport":"運費", "Quantity":"數量", "Style":"風格", "Detail":"商品說明", "Norm":"規格", "Memo":"備註"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得 API 內帳號資料
        5、輸出完整資料
        {"Result":"狀態","ShopID":"商品編號"}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
        $this->system->action = '[judge]';
        $db = with(new shop_repository())->addShopltem($this->system->title, $this->system->subtitle, $this->system->menuID, $this->system->price, $this->system->points, $this->system->transport, $this->system->quantity, $this->system->style, $this->system->detail, $this->system->norm, $this->system->memo);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        foreach($db as $row){
            $this->system->shopID = $row->ShopID;
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

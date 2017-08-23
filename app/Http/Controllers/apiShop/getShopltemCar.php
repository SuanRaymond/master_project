<?php

namespace App\Http\Controllers\ApiShop;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\shop_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class getShopltemCar extends Controller
{
	//共用參數
    public $system;


    /**
        登入
        1、從對應 API 接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"ShopID":{"0":"商品編號","1":"商品編號"}}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得DB 內帳號資料
        5、輸出完整資料
        {"Result":狀態,"ShopltemDetail":{"商品編號":{"shopID":"商品編號","title":"標題","subtitle":"副標","quantity":"數量","style":"風格","price":"售價","points":"積分","transport":"運費","menuID":"商品類別ID","orderID":"排序","useinfo":"致能","detail":"商品說明","norm":"規格","memo":"備註","bDate":"上架時間"}}}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
        $this->system->action = '[judge]';

        $shopCarID = $this->system->shopID;
        $this->system->shopltemCar = (object) array();

        foreach($shopCarID as $row){
            $db = with(new shop_repository())->getShopltemCar($row);

            if(empty($db)){
                with(new api_respone_services())->reAPI(500, $this->system);
            }

            foreach($db as $row){
                $shopID = $row->sShopID;
                $_row = clone $row;
                foreach($_row as $key => $value){
                    $tempKey = $key;
                    $key = substr($key, 1);
                    $row->$key = $value;
                    unset($row->$tempKey);
                }
                $this->system->shopltemCar->$shopID = reSetKey($row);
                unset($row->ShopID);
            }
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

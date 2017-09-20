<?php

namespace App\Http\Controllers\ApiShop;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\shop_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class commodityOrder extends Controller
{
	//共用參數
    public $system;

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    /**
        登入
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MemberID":"123","Item":{"商品編號":"數量","商品編號":"數量",......}})
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得 API 內帳號資料
        5、輸出完整資料
        {"Result":"狀態","ShoporderID":{"商品編號":"訂單編號",.....}
     */

    public function add()
    {
        $this->system->action = '[judge]';
        $this->system->shoporderID = (object) array();

        $item = $this->system->item;

        foreach($item as $index => $row){
            $db = with(new shop_repository())->addMemberCommodityOrder($this->system->memberID, $index, $row);

            if(empty($db)){
                with(new api_respone_services())->reAPI(500, $this->system);
            }

            foreach($db as $row){
                $this->system->shoporderID->$index = $row->shoporderID;
            }
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }

    /**
        登入
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"memberID":"會員編號","shoporderID":"訂單編號","status":"狀態"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得 API 內帳號資料
        5、輸出完整資料
        {"Result":"狀態"}
    */
    
    public function update()
    {
        $this->system->action = '[judge]';

        $db = with(new shop_repository())->updateMemberCommodityOrder($this->system->memberID, $this->system->shoporderID, $this->system->status);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $this->system->result = $row->result;
        }

        if($this->system->result != 0){
            with(new api_respone_services())->reAPI(501, $this->system);
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }

        /**
        登入
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"memberID":"會員編號","shoporderID":"訂單編號","status":"狀態"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得 API 內帳號資料
        5、輸出完整資料
        {"Result":"狀態"}
    */
    
    public function list()
    {
        $this->system->action = '[judge]';

        $db = with(new shop_repository())->getMemberCommodityOrderList($this->system->memberID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        $this->system->list = (object) Array();

        foreach($db as $row ){
            $shoporderID = $row->mcShoporderID;

            $_row = clone $row;
            foreach($_row as $key => $value){
                $tempKey = $key;
                $key = substr($key, 2);
                $row->$key = $value;
                unset($row->$tempKey);
            }

            $this->system->list->$shoporderID = reSetKey($row);
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

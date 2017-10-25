<?php

namespace App\Http\Controllers\ApiShop;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\shop_repository;
use App\Repository\member_repository;

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
            A：Params：加密後的資料JSON（{"MemberID":"123","Addressee":"收件人","Phone":"電話","Address":"地址","Item":{"商品編號":"數量","商品編號":"數量",......}})
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
            $db = with(new shop_repository())->addMemberCommodityOrder($this->system->memberID, $index, $row, $this->system->addressee, $this->system->phone, $this->system->address);

            if(empty($db)){
                with(new api_respone_services())->reAPI(500, $this->system);
            }

            foreach($db as $row){
                $this->system->shoporderID->$index = $row->shoporderID;

                //改訂購狀態
                with(new shop_repository())->updateMemberCommodityOrder($this->system->memberID, $row->shoporderID, 0);
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
            A：Params：加密後的資料JSON（{"memberID":"會員編號"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得 API 內帳號資料
        5、輸出完整資料
        {"Result":"List":{"訂單編號":{"shoporderID":"訂單編號","memberID":"會員編號","title":"商品名稱","price":"售價","points":"點數","status":"商品狀態","bDate":"購買日期","images":"圖片"}}}
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

    /**
        登入
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MemberID":"會員編號","ShopOrderID":{"0":"訂單編號","1":"訂單編號"}}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得 API 內帳號資料
        5、輸出完整資料
        {"Result":"結果","PayDetail":{"MN":"交易金額","OrderInfo":"交易內容","Td":"商家訂單編號","sna":"消費者姓名","sdt":"消費者電話","email":"消費者Email","note1":"備註","note2":"備註","Card_Type":"交易類別"}}
    */

    public function payCard()
    {
        $this->system->action = '[judge]';

        $MN = 0;
        $payID = NULL;
        $shopOrderID = $this->system->shoporderID;

        //計算總金額
        foreach($shopOrderID as $key => $value){
            $db = with(new shop_repository())->getMemberCommodityOrderDetail($value);

            foreach($db as $row){
                $MN += $row->totalPrice;
            }
        }

        //轉台幣 四捨五入 取整數
        $MN = round($MN * 31);

        foreach($shopOrderID as $key => $value){
            $db = with(new shop_repository())->getMemberShopPay($this->system->memberID, $value, $payID, 0, $MN);

            foreach($db as $row){
                $payID = $row->payID;
                if($row->result != 0){
                    with(new api_respone_services())->reAPI(540, $this->system);
                }
            }
        }

        $db = with(new member_repository())->getMemberDetail($this->system->memberID);

        //將資料空白去除
        foreach($db as $row){
            $this->system->member = reSetKey($row);
        }

        if($MN == 0){
            with(new api_respone_services())->reAPI(541, $this->system);
        }
        if(is_null($payID)){
            with(new api_respone_services())->reAPI(542, $this->system);
        }

        //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        $this->system->payDetail            = (object) array();
        $this->system->payDetail->MN        = $MN;
        $this->system->payDetail->OrderInfo = "FunMugle商城購物";
        $this->system->payDetail->Td        = $payID;
        $this->system->payDetail->sna       = $this->system->member->mname;
        $this->system->payDetail->sdt       = $this->system->member->maccount;
        $this->system->payDetail->email     = $this->system->member->mmail;
        $this->system->payDetail->note1     = "";
        $this->system->payDetail->note2     = "";
        $this->system->payDetail->Card_Type = 1;///////////test

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

<?php

namespace App\Http\Controllers\apiManager\report;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\shop_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class shopOrderList extends Controller
{
	//共用參數
    public $system;

    /**
        取得購買報表
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MineAccount":"搜尋者帳號","Account":"被搜尋者帳號","DownType":"是否搜尋下層",
                                      "ShopType":"購買單狀態","MinPay":"最低購買金額","MaxPay":"最高購買金額",
                                      "StartDate":"開始時間","EndDate":"結束時間","Row":"取得幾行","Page":"第幾頁"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得 API 內帳號資料
        5、輸出完整資料
        {"Result":"狀態","Menu":{"編號":"導向位置","編號":"導向位置"}}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
        $this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['SMLC-ID', 'CSHOPTYPE', 'CPAY']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new shop_repository())->
              getOrderListCount($this->system->mineMemberID, $this->system->memberID, $this->system->downtype,
                                $this->system->shoptype, $this->system->minpay, $this->system->maxpay,
                                $this->system->start, $this->system->end, $this->system->row, $this->system->page);

        $count                       = 0;
        $this->system->shopOrderList = [];
        foreach($db as $row){
            $count = $row->DataCount;
        }

        if($count > 0){
            $db = with(new shop_repository())->
              getOrderListSearch($this->system->mineMemberID, $this->system->memberID, $this->system->downtype,
                                $this->system->shoptype, $this->system->minpay, $this->system->maxpay,
                                $this->system->start, $this->system->end, $this->system->row, $this->system->page);

            foreach($db as $key => $row){
                $this->system->shopOrderList[] = reSetKey($row);
            }
        }

        $this->system->shopOrderList['count'] = $count;
        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

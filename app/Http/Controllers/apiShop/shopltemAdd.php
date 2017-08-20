<?php

namespace App\Http\Controllers\ApiShop;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Services\connection_services;
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
        {"Result":"狀態","Menu":{MenuID":"編號",MenuID":"編號",MenuID":"編號",MenuID":"編號",MenuID":"編號"}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
    	$this->system->action = '[judge]';
        $api_judge_services = new api_judge_services($this->system);

        /*----------------------------------與廠商溝通----------------------------------*/
        //放入連線區塊
        $this->system->action = '[communication]';
        //需呼叫的功能
        $this->system->callFunction = 'ShopltemAdd';
        $this->system->sendApiUrl   = config('app.urlMemberApi');
        $this->system->sendApiUrl   = json_decode($this->system->sendApiUrl, true);

        //放入資料區塊
        $this->system->action                   = '[communication_setdata]';
        $this->system->sendParams               = [];
        $this->system->sendParams['Title']      = $this->system->title;
        $this->system->sendParams['SubTitle']   = $this->system->subtitle;
        $this->system->sendParams['MenuID']     = $this->system->menuID;
        $this->system->sendParams['Price']      = $this->system->price;
        $this->system->sendParams['Points']     = $this->system->points;
        $this->system->sendParams['Transport']  = $this->system->transport;
        $this->system->sendParams['Quantity']   = $this->system->quantity;
        $this->system->sendParams['Style']      = $this->system->style;
        $this->system->sendParams['Detail']     = $this->system->detail;
        $this->system->sendParams['Norm']       = $this->system->norm;
        $this->system->sendParams['Memo']       = $this->system->memo;

        //送出資料
        $this->system->action    = '[communication_send_post]';
        $this->system->result    = with(new connection_services())->callApi($this->system);
        $this->system->getResult = $this->system->result;

        //檢查廠商回傳資訊
        $this->system->action       = '[communication_judge]';
        $api_judge_services->system = $this->system;
        $this->system               = $api_judge_services->check(['CAPI']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }
        /*----------------------------------與廠商溝通----------------------------------*/
        //整理輸出資料
        $this->system->action = '[reorderdata]';
        $this->system->shopID = $this->system->result->ShopID;

    	with(new api_respone_services())->reAPI(0, $this->system);
    }
}

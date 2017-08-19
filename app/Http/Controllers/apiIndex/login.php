<?php

namespace App\Http\Controllers\ApiIndex;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Services\connection_services;
use App\Services\api_judge_services;
use App\Services\api_respone_services;
class login extends Controller
{
	//共用參數
    public $system;

    /**
        登入
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"Account":"帳號","Password":"密碼"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得 API 內帳號資料
        5、輸出完整資料
        {"Result":"狀態","Member":{"account":"帳號","name":"暱稱","points":"點數","integral":"積分","bonus":"紅利","memberID":"會員編號","LanguageID":"語言"}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
    	$this->system->action = '[judge]';
        $api_judge_services = new api_judge_services($this->system);
        $this->system = $api_judge_services->check(['CMA', 'CPW']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        /*----------------------------------與廠商溝通----------------------------------*/
        //放入連線區塊
        $this->system->action = '[communication]';
        //需呼叫的功能
        $this->system->callFunction = 'Login';
        $this->system->sendApiUrl   = config('app.urlMemberApi');
        $this->system->sendApiUrl   = json_decode($this->system->sendApiUrl, true);

        //放入資料區塊
        $this->system->action                 = '[communication_setdata]';
        $this->system->sendParams             = [];
        $this->system->sendParams['Account']  = $this->system->account;
        $this->system->sendParams['Password'] = $this->system->password;

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
        $this->system->member = $this->system->result->Member;

    	with(new api_respone_services())->reAPI(0, $this->system);
    }
}

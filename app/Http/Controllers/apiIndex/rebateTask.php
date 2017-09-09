<?php

namespace App\Http\Controllers\ApiIndex;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Services\connection_services;
use App\Services\api_judge_services;
use App\Services\api_respone_services;
class rebateTask extends Controller
{
	//共用參數
    public $system;

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

     /**
        寫入會員登入資料
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MemberID":"會員唯一碼"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、密碼加密
        4、存入資料庫
        5、輸出完整資料
        {"Result":0, "Status":"狀態"}
     */
    
    public function today()
    {
    	$this->system->action = '[judge]';
        $api_judge_services = new api_judge_services($this->system);
        $this->system = $api_judge_services->check(['CMID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        /*----------------------------------與廠商溝通----------------------------------*/
        //放入連線區塊
        $this->system->action = '[communication]';
        //需呼叫的功能
        $this->system->callFunction = 'GetRebateTaskToday';
        $this->system->sendApiUrl   = config('app.urlMemberApi');
        $this->system->sendApiUrl   = json_decode($this->system->sendApiUrl, true);

        //放入資料區塊
        $this->system->action                       = '[communication_setdata]';
        $this->system->sendParams                   = [];
        $this->system->sendParams['MemberID']       = $this->system->memberID;

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
        $this->system->status = $this->system->result->Status;

    	with(new api_respone_services())->reAPI(0, $this->system);
    }

         /**
        寫入會員登入資料
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MemberID":"會員唯一碼"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、密碼加密
        4、存入資料庫
        5、輸出完整資料
        {"Result":0, "Checkin":"簽到狀態", "ScratchCard":"刮刮樂狀態"}
     */
    
    public function list()
    {
        $this->system->action = '[judge]';
        $api_judge_services = new api_judge_services($this->system);
        $this->system = $api_judge_services->check(['CMID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        /*----------------------------------與廠商溝通----------------------------------*/
        //放入連線區塊
        $this->system->action = '[communication]';
        //需呼叫的功能
        $this->system->callFunction = 'GetRebateTaskList';
        $this->system->sendApiUrl   = config('app.urlMemberApi');
        $this->system->sendApiUrl   = json_decode($this->system->sendApiUrl, true);

        //放入資料區塊
        $this->system->action                       = '[communication_setdata]';
        $this->system->sendParams                   = [];
        $this->system->sendParams['MemberID']       = $this->system->memberID;

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
        $this->system->checkin = $this->system->result->Checkin;
        $this->system->scratchCard = $this->system->result->ScratchCard;
        $this->system->checkinCount = $this->system->result->CheckinCount;

        with(new api_respone_services())->reAPI(0, $this->system);
    }

         /**
        寫入會員登入資料
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MemberID":"會員唯一碼"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、密碼加密
        4、存入資料庫
        5、輸出完整資料
         {"Result":0}
     */
    
    public function checkin()
    {
        $this->system->action = '[judge]';
        $api_judge_services = new api_judge_services($this->system);
        $this->system = $api_judge_services->check(['CMID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        /*----------------------------------與廠商溝通----------------------------------*/
        //放入連線區塊
        $this->system->action = '[communication]';
        //需呼叫的功能
        $this->system->callFunction = 'CheckinRebateTask';
        $this->system->sendApiUrl   = config('app.urlMemberApi');
        $this->system->sendApiUrl   = json_decode($this->system->sendApiUrl, true);

        //放入資料區塊
        $this->system->action                       = '[communication_setdata]';
        $this->system->sendParams                   = [];
        $this->system->sendParams['MemberID']       = $this->system->memberID;

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

        $this->system->result = $this->system->result->Result;

        with(new api_respone_services())->reAPI(0, $this->system);
    }

         /**
        寫入會員登入資料
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MemberID":"會員唯一碼","Type":"刮刮樂種類"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、密碼加密
        4、存入資料庫
        5、輸出完整資料
        {"Result":0,"scratchID":"卡號","odds":"倍率"}
     */
    
    public function scratchCard()
    {
        $this->system->action = '[judge]';
        $api_judge_services = new api_judge_services($this->system);
        $this->system = $api_judge_services->check(['CMID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        /*----------------------------------與廠商溝通----------------------------------*/
        //放入連線區塊
        $this->system->action = '[communication]';
        //需呼叫的功能
        $this->system->callFunction = 'GetRebateTaskScratchCard';
        $this->system->sendApiUrl   = config('app.urlMemberApi');
        $this->system->sendApiUrl   = json_decode($this->system->sendApiUrl, true);

        //放入資料區塊
        $this->system->action                       = '[communication_setdata]';
        $this->system->sendParams                   = [];
        $this->system->sendParams['MemberID']       = $this->system->memberID;
        $this->system->sendParams['Type']           = $this->system->type;

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
        $this->system->moneyBack = $this->system->result->MoneyBack;
        $this->system->scratchID = $this->system->result->ScratchID;
        $this->system->type      = $this->system->result->Type;
        $this->system->taskOdds  = $this->system->result->TaskOdds;
        $this->system->oddsDetail  = $this->system->result->OddsDetail;
dd(1111);
        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

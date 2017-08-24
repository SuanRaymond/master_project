<?php

namespace App\Http\Controllers\ApiIndex;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Services\connection_services;
use App\Services\api_judge_services;
use App\Services\api_respone_services;
class verificationReSend extends Controller
{
	//共用參數
    public $system;

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

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
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
        $this->system->callFunction = 'VerificationReSend';
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
        $this->system->result = $this->system->result->Result;

    	with(new api_respone_services())->reAPI(0, $this->system);
    }
}

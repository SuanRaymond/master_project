<?php

namespace App\Http\Controllers\ApiMember;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\member_repository;

use App\Services\connection_services;
use App\Services\api_judge_services;
use App\Services\api_respone_services;

class verificationReSend extends Controller
{
	//共用參數
    public $system;

    /**
        修改會員資料
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MemberID":"會員唯一碼"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、密碼加密
        4、存入資料庫
        5、輸出完整資料
        {"Result":狀態}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
    	$this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMID', 'SMGDS']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())->getVerificationDate($this->system->memberID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

       //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $this->system->verificationDate = $row->mCDate;
        }

        //未到驗證碼開通時間
        if(strtotime(date("Y-m-d H:i:s")) - strtotime($this->system->verificationDate) < 0){
            with(new api_respone_services())->reAPI(520, $this->system);
        }

        $this->system->verification = str_random(6);

        $db = with(new member_repository())->updateVerificationDate($this->system->memberID, $this->system->verification);

        //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $this->system->result = $row->result;
        }
        //更新驗證碼失敗
        if($this->system->result != 0){
            with(new api_respone_services())->reAPI(521, $this->system);
        }

        //發送驗證碼
        $msg = '感谢您在 FunMugle 注册会员，您的验证码为『'. $this->system->verification. '』请立即输入验证，谢谢。';
        $this->system->postArray   = http_build_query(
            array(
                'username'  => env('SEND_MAIL_ACCOUNT'),
                'password'  => env('SEND_MAIL_PASSWORD'),
                'method'    => 1,
                'sms_msg'   => $msg,
                'phone'     => $this->system->member->maccount,
                'send_date' => date('Y/m/d'),
                'hour'      => date('H'),
                'min'       => date('i'),
        ));

        $this->system->result = 0;
        with(new connection_services())->sendHTTP(env('SEND_MAIL_URL'), $this->system->postArray);

    	with(new api_respone_services())->reAPI(0, $this->system);
    }
}

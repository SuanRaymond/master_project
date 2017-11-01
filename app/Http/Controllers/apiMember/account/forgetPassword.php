<?php

namespace App\Http\Controllers\ApiMember\account;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\member_repository;

use App\Services\connection_services;
use App\Services\api_judge_services;
use App\Services\api_respone_services;
class forgetPassword extends Controller
{
	//共用參數
    public $system;

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    /**
        忘記密碼－送出驗證碼
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"Account":"帳號"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、密碼加密
        4、存入資料庫
        5、輸出完整資料
        {"Result":0}
    */
    public function send()
    {
        $this->system->action = '[judge]';
        $api_judge_services = new api_judge_services($this->system);
        $this->system = $api_judge_services->check(['CMA', 'SMIDG']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        //產出６碼數字驗證碼
        $this->system->code = rand(100000,999999);

        //寫入資料庫
        $db = with(new member_repository())->setFrogetPasswordCode($this->system->memberID, $this->system->code);
        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }
        //發送驗證碼
        $msg = '『FunMugle』找回您的密码，您的验证码为『'. $this->system->code. '』请立即输入验证，谢谢。';
        with(new connection_services())->sendSMS($msg, $this->system->account);

        $this->system->result = 0;
        with(new api_respone_services())->reAPI(0, $this->system);
    }

    /**
        忘記密碼－更新密碼
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"Account":"帳號","Password":"密碼","Verification":"驗證碼"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、密碼加密
        4、存入資料庫
        5、輸出完整資料
        {"Result":0}
    */
    public function change()
    {
        $this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMA', 'CPW', 'CMV', 'SMIDG']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        //比對驗證碼是否正確
        $db = with(new member_repository())->setFrogetPasswordPassword($this->system->memberID, $this->system->verification, $this->system->password);
        //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $this->system->result = $row->result;
        }
        //驗證錯誤
        if($this->system->result == 1){
            with(new api_respone_services())->reAPI(510, $this->system);
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }


}

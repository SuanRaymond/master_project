<?php

namespace App\Http\Controllers\ApiMember;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\member_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;

class verificationCheck extends Controller
{
	//共用參數
    public $system;

    /**
        修改會員資料
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON
            （{"MemberID":"會員唯一碼","Verification":"驗證碼"}）
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
        $this->system = with(new api_judge_services($this->system))->check(['CMID', 'CMV']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())->checkVerification($this->system->memberID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }
        //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $this->system->mVerification = $row->mVerification;
        }

        //驗證碼不符
        if(strtoupper($this->system->mVerification) != strtoupper($this->system->verification)){
            with(new api_respone_services())->reAPI(510, $this->system);
        }

        //清除驗證碼
        with(new member_repository())->clearVerification($this->system->memberID);

        $this->system->result = 0;
    	with(new api_respone_services())->reAPI(0, $this->system);
    }
}

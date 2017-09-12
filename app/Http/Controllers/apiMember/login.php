<?php

namespace App\Http\Controllers\ApiMember;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\member_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class login extends Controller
{
	//共用參數
    public $system;

    /**
        登入
        1、從對應 API 接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"Account":"帳號","Password":"密碼"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得DB 內帳號資料
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
        $this->system = with(new api_judge_services($this->system))->check(['CMA', 'CPW', 'SMG']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        //比對密碼
        $this->system->action = '[check_password]';
        if($this->system->member->mpassword != strtoupper(md5($this->system->password))){
            with(new member_repository())->addPasswordErrorCount($this->system->member->mmemberID);
            with(new api_respone_services())->reAPI(10, $this->system);
        }

        //比對密碼錯誤次數
        if($this->system->member->mpassworderrorcount > 6){
            with(new api_respone_services())->reAPI(11, $this->system);
        }

        //比對帳號是否可用
        $this->system->action = '[check_useinfo]';
        if($this->system->member->museinfo != 1){
            with(new api_respone_services())->reAPI(12, $this->system);
        }

        //清除密碼錯誤次數
        $this->system->action = '[clear_errorcount]';
        with(new member_repository())->clearPasswordErrorCount($this->system->member->mmemberID);

        //驗證身份
        $this->system->action = '[check_verification]';
        if($this->system->member->mverification != ""){
            $memberID = $this->system->member->mmemberID;
            $this->system->member = (object) array();
            $this->system->member->memberID = $memberID;
            with(new api_respone_services())->reAPI(13, $this->system);
        }


		//整理輸出資料
        $this->system->action = '[clear_machdata]';
		unset($this->system->member->mpassword);
		unset($this->system->member->mpassworderrorcount);
		unset($this->system->member->museinfo);

        //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        $db            = clone $this->system->member;
        foreach($db as $key => $value){
            $tempKey                    = $key;
            $key                        = substr($key, 1);
            $this->system->member->$key = $value;
            unset($this->system->member->$tempKey);
        }

        $this->system->member = reSetKey($this->system->member);

    	with(new api_respone_services())->reAPI(0, $this->system);
    }
}

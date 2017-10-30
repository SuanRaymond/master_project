<?php

namespace App\Http\Controllers\ApiMember;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\member_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class detail extends Controller
{
	//共用參數
    public $system;

    /**
        登入
        1、從對應 API 接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MemberID":"會員唯一碼"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得DB 內帳號資料
        5、輸出完整資料
        {"Result":"狀態","Member":{"account":"帳號","name":"暱稱","points":"點數","integral":"積分","bonus":"紅利","memberID":"會員編號","mail":"信箱","address":"地址","birthday":"生日","gender":"性別","BankName":"銀行姓名","Bank":"銀行名稱","BankID":"銀行代號","cardID":"銀行卡","LanguageID":"語言","UpID":"上層會員編號"}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
    	$this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMID', 'SMGD']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        $db            = clone $this->system->member;
        foreach($db as $key => $value){
            $tempKey                    = $key;
            $key                        = substr($key, 1);
            $this->system->member->$key = $value;
            unset($this->system->member->$tempKey);
        }

    	with(new api_respone_services())->reAPI(0, $this->system);
    }
}

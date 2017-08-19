<?php

namespace App\Http\Controllers\ApiMember;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\member_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class passwordUpdate extends Controller
{
	//共用參數
    public $system;

    /**
        登入
        1、從對應 API 接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MemberID":"會員唯一碼","PasswordO":"舊密碼","PasswordN":"新密碼"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得DB 內帳號資料
        5、輸出完整資料
        {"Result":"狀態"}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
    	$this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMID', 'CPWON']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }


        $db = with(new member_repository())->updatePassword($this->system->memberID, $this->system->passwordo, $this->system->passwordn);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $this->system->result = $row->result;
        }

        //密碼錯誤
        if($this->system->result == 2){
            with(new api_respone_services())->reAPI(268, $this->system);
        }
        else if($this->system->result != 0){
            with(new api_respone_services())->reAPI(501, $this->system);
        }

    	with(new api_respone_services())->reAPI(0, $this->system);
    }
}

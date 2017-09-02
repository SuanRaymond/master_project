<?php

namespace App\Http\Controllers\ApiMember;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\member_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class rebateAdd extends Controller
{
	//共用參數
    public $system;

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    /**
        登入
        1、從對應 API 接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MemberID":"會員唯一碼"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得DB 內帳號資料
        5、輸出完整資料
        {"Result":0,"Type":"種類","Point":"所需點數","MoneyBack":"返利金額"}
     */
    
    public function rebateList()
    {
    	$this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())->getRebateList($this->system->memberID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        $this->system->rebateList = (object) array();
       //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $type = $row->srType;
            $this->system->rebateList->$type = (object) array();
            $this->system->rebateList->$type->Type = $row->srType;
            $this->system->rebateList->$type->Point = $row->srPoint;
            $this->system->rebateList->$type->MoneyBack = $row->srMoneyBack;
        }

    	with(new api_respone_services())->reAPI(0, $this->system);
    }

        /**
        登入
        1、從對應 API 接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MemberID":"會員唯一碼", "RebateType":"購買種類"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得DB 內帳號資料
        5、輸出完整資料
        {"Result":0}
     */
    
    public function rebateAdd()
    {
        $this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())->addRebate($this->system->memberID, $this->system->rebatetype);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

       //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $this->system->result = $row->result;
        }

        //返利模式不存在
        if($this->system->result == 2){
            with(new api_respone_services())->reAPI(530, $this->system);
        }
        //點數不足
        else if($this->system->result == 3){
            with(new api_respone_services())->reAPI(531, $this->system);
        }
        //已購買返利
        else if($this->system->result == 4){
            with(new api_respone_services())->reAPI(532, $this->system);
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

<?php

namespace App\Http\Controllers\ApiMember;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\member_repository;

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
        登入
        1、從對應 API 接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MemberID":"會員唯一碼"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得DB 內帳號資料
        5、輸出完整資料
        {"Result":0, "Status":"狀態"}
     */
    
    public function today()
    {
    	$this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())->getRebateTaskToday($this->system->memberID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

       //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $this->system->status = $row->status;
        }

    	with(new api_respone_services())->reAPI(0, $this->system);
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
        {"Result":0, "Checkin":"簽到狀態", "ScratchCard":"刮刮樂狀態"}
     */
    
    public function list()
    {
        $this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())->getRebateTaskList($this->system->memberID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

       //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $this->system->result = $row->result;
            $this->system->checkin = $row->checkin;
            $this->system->scratchCard = $row->scratchCard;
            $this->system->checkinCount = $row->checkinCount;
        }

        //未購買返利
        if($this->system->result == 2){
            with(new api_respone_services())->reAPI(533, $this->system);
        }

        with(new api_respone_services())->reAPI(0, $this->system);
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
        {"Result":0}
     */
    
    public function checkin()
    {
        $this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())->checkinRebateTask($this->system->memberID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

       //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $this->system->result = $row->result;
        }

        //未購買返利
        if($this->system->result == 2){
            with(new api_respone_services())->reAPI(533, $this->system);
        }
        //今日已簽到過
        else if($this->system->result == 3){
            with(new api_respone_services())->reAPI(534, $this->system);
        }

        with(new api_respone_services())->reAPI(0, $this->system);
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
        {"Result":0,"moneyBack":"返利", "scratchID":"卡號","odds":"倍率"}
     */
    
    public function scratchCard()
    {
        $this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())->getRebateTaskScratchCard($this->system->memberID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

       //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $this->system->result = $row->result;
            $this->system->moneyBack = $row->moneyBack;
            $this->system->scratchID = $row->scratchID;
            $this->system->taskOdds = $row->taskOdds;
        }

        //未購買返利
        if($this->system->result == 2){
            with(new api_respone_services())->reAPI(533, $this->system);
        }
        //已返利過
        else if($this->system->result == 3){
            with(new api_respone_services())->reAPI(535, $this->system);
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

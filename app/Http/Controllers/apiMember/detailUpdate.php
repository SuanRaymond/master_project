<?php

namespace App\Http\Controllers\ApiMember;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\member_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;

class detailUpdate extends Controller
{
	//共用參數
    public $system;

    /**
        修改會員資料
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON
            （{"MemberID":"會員唯一碼","Name":"暱稱","Mail":"信箱","Address":"地址","Birthday":"生日","Gender":"性別",
                "LanguageID":"語言","CardID":"卡號"}）
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
        $this->system = with(new api_judge_services($this->system))->check(['CMN', 'CMM', 'CMAD', 'CMB', 'CMGD', 'CML', 'CMC']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())
                ->updateMemberDetail($this->system->memberID, $this->system->name, $this->system->mail,
                                    $this->system->address, $this->system->birthday,$this->system->gender, 
                                    $this->system->languageID, $this->system->cardID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $this->system->result = $row->result;
        }

        if($this->system->result != 0){
            with(new api_respone_services())->reAPI(501, $this->system);
        }

    	with(new api_respone_services())->reAPI(0, $this->system);
    }
}

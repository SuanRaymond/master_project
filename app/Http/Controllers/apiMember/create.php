<?php

namespace App\Http\Controllers\ApiMember;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\member_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class create extends Controller
{
	//共用參數
    public $system;

    /**
        建立會員
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON
            （{"Account":"帳號","Name":"暱稱","Password":"密碼","Mail":"信箱","upMemberID":"上層會員","groupID":"權限代碼"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、密碼加密
        4、存入資料庫
        5、輸出完整資料
        {"Result":狀態,"MemberID":"會員編號"}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
    	$this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMA', 'CPW', 'CMN', 'CMM', 'SMRG', 'SMRM', 'SCG', 'SMUG']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())
                ->addMember($this->system->account, $this->system->name, $this->system->password,
                            $this->system->mail, $this->system->upmemberID, $this->system->groupID);

        $this->system->action = '[reorderdata]';
        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }
        foreach($db as $row){
            $this->system->memberID = $row->memberID;
            if($this->system->memberID < 0){
                with(new api_respone_services())->reAPI(501, $this->system);
            }
        }

    	with(new api_respone_services())->reAPI(0, $this->system);
    }
}

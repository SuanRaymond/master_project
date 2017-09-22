<?php

namespace App\Http\Controllers\apiManager\account;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\member_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class accountList extends Controller
{
	//共用參數
    public $system;

    /**
        取得會員資料列表
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MineAccount":"搜尋者帳號","Account":"被搜尋者帳號","DownType":"是否搜尋下層","Row":"取得幾行","Page":"第幾頁"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得 API 內帳號資料
        5、輸出完整資料
        {"Result":"狀態","Menu":{"編號":"導向位置","編號":"導向位置"}}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
        $this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['SMLC-ID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())->
              getAccountList($this->system->mineMemberID, $this->system->memberID, $this->system->downtype, $this->system->row, $this->system->page);

        $this->system->accountList = (object) array();

        foreach($db as $row){
            $lev = $row->Lev;
            $row = reSetKey($row);

            if($lev == 0){
                $lev = 'min';
            }
            else if($lev == 1){
                $lev = 'dow';
            }
            if(!isset($this->system->accountList->$lev)){
                $this->system->accountList->$lev          = [];
                $this->system->accountList->$lev['count'] = $row->rowmax;
            }
            unset($row->rowmax);
            $this->system->accountList->$lev[] = $row;
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

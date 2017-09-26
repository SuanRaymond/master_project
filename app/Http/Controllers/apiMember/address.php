<?php

namespace App\Http\Controllers\ApiMember;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\member_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;

class address extends Controller
{
	//共用參數
    public $system;

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    /**
        修改會員資料
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON
            （{"MemberID":"會員唯一碼","addressee":"收件人","phone":"電話","Address":"地址"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、密碼加密
        4、存入資料庫
        5、輸出完整資料
        {"Result":狀態}
     */
    
    public function add()
    {
    	$this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())->getMemberAddress($this->system->memberID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        $one    = 1;
        $two    = 2;
        $three  = 3;
        $four   = 4;
        $five   = 5;
        $this->system->list         = (object) array();
        $this->system->list->$one   = (object) array();
        $this->system->list->$two   = (object) array();
        $this->system->list->$three = (object) array();
        $this->system->list->$four  = (object) array();
        $this->system->list->$five  = (object) array();

        foreach($db as $row){
            $_row = reSetKey($row);
            $this->system->list->defaultID          = $_row->mdefaultID;
            $this->system->list->$one->addressee    = $_row->maddressee1;
            $this->system->list->$one->phone        = $_row->mphone1;
            $this->system->list->$one->address      = $_row->maddress1;
            $this->system->list->$two->addressee    = $_row->maddressee2;
            $this->system->list->$two->phone        = $_row->mphone2;
            $this->system->list->$two->address      = $_row->maddress2;
            $this->system->list->$three->addressee  = $_row->maddressee3;
            $this->system->list->$three->phone      = $_row->mphone3;
            $this->system->list->$three->address    = $_row->maddress3;
            $this->system->list->$four->addressee   = $_row->maddressee4;
            $this->system->list->$four->phone       = $_row->mphone4;
            $this->system->list->$four->address     = $_row->maddress4;
            $this->system->list->$five->addressee   = $_row->maddressee5;
            $this->system->list->$five->phone       = $_row->mphone5;
            $this->system->list->$five->address     = $_row->maddress5;
        }

        $db = clone $this->system->list;
        unset($db->defaultID);

        $this->system->result = -1;
        foreach($db as $index => $row){
            if($row->addressee == '' && $row->phone == '' && $row->address == ''){
                $result = with(new member_repository())->updateMemberAddress($this->system->memberID, $index, $this->system->addressee, $this->system->phone, $this->system->address, 0);

                if(empty($result)){
                    with(new api_respone_services())->reAPI(500, $this->system);
                }
                foreach($result as $row){
                    $this->system->result = $row->result;
                }
                if($this->system->result != 0){
                    with(new api_respone_services())->reAPI(501, $this->system);
                }
                break;
            }
        }

        //購物地址已滿
        if($this->system->result != 0){
            with(new api_respone_services())->reAPI(536, $this->system);
        }

    	with(new api_respone_services())->reAPI(0, $this->system);
    }

    /**
        登入
        1、從對應 API 接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"MemberID":"會員唯一碼","index":"第幾組","addressee":"收件人","phone":"電話","address":"地址","default":"是否為默認組"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得DB 內帳號資料
        5、輸出完整資料
        {"Result":0}
     */
    public function update()
    {
        $this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())->updateMemberAddress($this->system->memberID, $this->system->index, $this->system->addressee, $this->system->phone, $this->system->address, $this->system->default);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

       //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        foreach($db as $row){
            $this->system->result = $row->result;
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
        {"Result":狀態,"List":{"1":{"Addressee":"收件人","Phone":"電話","Address":"地址"},"2":{"Addressee":"收件人","Phone":"電話","Address":"地址"},"3":{"Addressee":"收件人","Phone":"電話","Address":"地址"},"4":{"Addressee":"收件人","Phone":"電話","Address":"地址"},"5":{"Addressee":"收件人","Phone":"電話","Address":"地址"},"defaultID":"默認組"}}
     */
    
    public function list()
    {
        $this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMID']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $db = with(new member_repository())->getMemberAddress($this->system->memberID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

       //將欄位名稱改變
        $this->system->action = '[reorderdata]';
        $one    = 1;
        $two    = 2;
        $three  = 3;
        $four   = 4;
        $five   = 5;
        $this->system->list         = (object) array();
        $this->system->list->$one   = (object) array();
        $this->system->list->$two   = (object) array();
        $this->system->list->$three = (object) array();
        $this->system->list->$four  = (object) array();
        $this->system->list->$five  = (object) array();

        foreach($db as $row){
            $_row = reSetKey($row);
            $this->system->list->defaultID          = $_row->mdefaultID;
            $this->system->list->$one->addressee    = $_row->maddressee1;
            $this->system->list->$one->phone        = $_row->mphone1;
            $this->system->list->$one->address      = $_row->maddress1;
            $this->system->list->$two->addressee    = $_row->maddressee2;
            $this->system->list->$two->phone        = $_row->mphone2;
            $this->system->list->$two->address      = $_row->maddress2;
            $this->system->list->$three->addressee  = $_row->maddressee3;
            $this->system->list->$three->phone      = $_row->mphone3;
            $this->system->list->$three->address    = $_row->maddress3;
            $this->system->list->$four->addressee   = $_row->maddressee4;
            $this->system->list->$four->phone       = $_row->mphone4;
            $this->system->list->$four->address     = $_row->maddress4;
            $this->system->list->$five->addressee   = $_row->maddressee5;
            $this->system->list->$five->phone       = $_row->mphone5;
            $this->system->list->$five->address     = $_row->maddress5;
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

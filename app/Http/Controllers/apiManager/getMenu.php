<?php

namespace App\Http\Controllers\apiManager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\admin_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class getMenu extends Controller
{
	//共用參數
    public $system;

    /**
        後台選單
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"GroupID":"權限代碼"}）
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
        $db = with(new admin_repository())->getMenu($this->system->groupID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(550, $this->system);
        }

        $this->system->menu = (object) array();

        foreach($db as $row){
            $menuID = intval(floor($row->managerMenuID / 100));
            $row    = reSetKey($row);
            if(!isset($this->system->menu->$menuID)){
                $this->system->menu->$menuID = [];
            }
            $this->system->menu->$menuID[$row->managermenuID] = $row->ssmpath;
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

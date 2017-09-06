<?php

namespace App\Http\Controllers\apiManager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\images_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class images extends Controller
{
	//共用參數
    public $system;

    /**
        後台取得圖片資訊
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"Title":"圖片檔名"}）
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

    public function search()
    {
        $this->system->action = '[search]';
        $db = with(new images_repository())->getImagesItem($this->system->title);

        if(empty($db)){
            with(new api_respone_services())->reAPI(550, $this->system);
        }

        $this->system->images = (object) array();

        foreach($db as $row){
            $this->system->images = reSetKey($row);
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }

    public function insert()
    {
        $this->system->action = '[insert]';
        $db = with(new images_repository())->insertImagesItem($this->system->title, $this->system->images);

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

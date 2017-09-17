<?php

namespace App\Http\Controllers\ApiManager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\commodity_repository;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class commodity extends Controller
{
	//共用參數
    public $system;

    /**
        登入
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON（{"Title":"標題","SubTitle":"副標","MenuID":"商品類別ID", "Price":"售價", "Points":"積分", "Transport":"運費", "Quantity":"數量", "Style":"風格", "Detail":"商品說明", "Norm":"規格", "Memo":"備註"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、比對帳號是否合法
        4、取得 API 內帳號資料
        5、輸出完整資料
        {"Result":"狀態","ShopID":"商品編號"}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function add()
    {
        $this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['MCT', "MCMID", "MCP", "MCPT", "MCTS", "MCQ"]);

        $db = with(new commodity_repository())->addCommodity($this->system->title, $this->system->subtitle, $this->system->menuID, $this->system->price, $this->system->points, $this->system->transport, $this->system->quantity, $this->system->style, $this->system->detail, $this->system->norm, $this->system->memo);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        foreach($db as $row){
            $this->system->shopID = $row->ShopID;
        }

        //主頁圖片
        $db = with(new commodity_repository())->InsertShopImages($this->system->shopID, 0, $this->system->imagestitle);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        //展示圖片
        $imagesShow = explode(",",$this->system->imagesshow);

        foreach($imagesShow as $row){
            $db = with(new commodity_repository())->InsertShopImages($this->system->shopID, 1, $row);

            if(empty($db)){
                with(new api_respone_services())->reAPI(500, $this->system);
            }
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }

    public function get()
    {
        $this->system->action = '[judge]';

        $db = with(new commodity_repository())->getCommodity($this->system->shopID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        $this->system->commodity = (object) array();
        $this->system->images = "";
        $this->system->imageStitle = "";
        $this->system->imagesShow = "";

        foreach($db as $rowID => $row){
            $_row = clone $row;
            foreach($_row as $key => $value){
                $tempKey = $key;
                $key = substr($key, 1);
                $row->$key = $value;
                unset($row->$tempKey);
            }

            $this->system->commodity = reSetKey($row);
            $_row = reSetKey($row);

            //捨棄不需要的資料
            if($_row->imagestype == 0){
                $this->system->images = $_row->images;
                $this->system->imageStitle = $_row->imagestitle;
            }
            else{
                $this->system->imagesShow = $this->system->imagesShow.$_row->imagestitle.",";
            }
        }

        $this->system->commodity->images = $this->system->images;
        $this->system->commodity->imagestitle = $this->system->imageStitle;
        $this->system->commodity->imagesshow = $this->system->imagesShow;

        with(new api_respone_services())->reAPI(0, $this->system);
    }

    public function update()
    {
        $this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['MCT', "MCMID", "MCP", "MCPT", "MCTS", "MCQ"]);

        $db = with(new commodity_repository())->updateCommodity($this->system->shopID,$this->system->title, $this->system->subtitle, $this->system->menuID, $this->system->price, $this->system->points, $this->system->transport, $this->system->quantity, $this->system->style, $this->system->detail, $this->system->norm, $this->system->memo);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        //清除圖片連結
        $db = with(new commodity_repository())->ClearShopImages($this->system->shopID);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        //主頁圖片
        $db = with(new commodity_repository())->InsertShopImages($this->system->shopID, 0, $this->system->imagestitle);

        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }

        //展示圖片
        $imagesShow = explode(",",$this->system->imagesshow);

        foreach($imagesShow as $row){
            $db = with(new commodity_repository())->InsertShopImages($this->system->shopID, 1, $row);

            if(empty($db)){
                with(new api_respone_services())->reAPI(500, $this->system);
            }
        }

        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

<?php
namespace App\Repository;

use DB;
//member SQL 輔助
class shop_repository{

    public $contStr = '';

    public function __construct()
    {
        if(strlen(env('DB_HOST')) > 10){
            $this->contStr = 'SET NOCOUNT ON; ';
        }
    }

    /**
     * 取MenuID
     */
    public function getMenu(){
        return DB::select($this->contStr. "EXEC SSP_ShopMenu", 
            array());
    }

    /**
     * 取商品清單
     * @param  int      $_menuID      menuID
     */
    public function getMenuCommodity($_menuID){
        return DB::select($this->contStr. "EXEC SSP_ShopMenuCommodity @_menuID=?", 
            array($_menuID));
    }

        /**
     * 取商品明細
     * @param  int      $_shopID      shopID
     */
    public function getShopltemDetail($_shopID){
        return DB::select($this->contStr. "EXEC SSP_ShopltemDetailShopID @_shopID=?", 
            array($_shopID));
    }

    /**
     * 取購物車商品
     * @param  int      $_shopID      shopID
     */
    public function getShopltemCar($_shopID){
        return DB::select($this->contStr. "EXEC SSP_ShopltemCarShopID @_shopID=?",
            array($_shopID));
    }

        /**
     * 新增會員訂單
     * @param  int      $_memberID       會員編號
     * @param  int      $_shopID         商品編號
     * @param  int      $_quantity       數量
     * @param  string   $_addressee      收件人
     * @param  string   $_phone          電話
     * @param  string   $_address        地址
     */
    public function addMemberCommodityOrder($_memberID, $_shopID, $_quantity, $_addressee, $_phone, $_address){
        return DB::select($this->contStr. "EXEC SSP_MemberCommodityOrderAdd @_memberID=?, @_shopID=?, @_quantity=?, @_addressee=?, @_phone=?, @_address=?",
            array($_memberID, $_shopID, $_quantity, $_addressee, $_phone, $_address));
    }

    /**
     * 修改會員訂單
     * @param  int      $_memberID        會員編號
     * @param  int      $_shoporderID     訂單編號
     * @param  int      $_status          狀態
     */
    public function updateMemberCommodityOrder($_memberID, $_shoporderID, $_status){
        return DB::select($this->contStr. "EXEC SSP_MemberCommodityOrderUpdate @_memberID=?, @_shoporderID=?, @_status=?",
            array($_memberID, $_shoporderID, $_status));
    }

    /**
     * 取得會員訂單清單
     * @param  int      $_memberID        會員編號
     */
    public function getMemberCommodityOrderList($_memberID){
        return DB::select($this->contStr. "EXEC SSP_MemberCommodityOrderList @_memberID=?",
            array($_memberID));
    }





    /**
     * 後台取得會員訂單清單-總數
     * @param  int  $_minmemberID 搜尋者的編號
     * @param  int  $_memberID    被搜尋者的編號
     * @param  int  $_downType    是否搜尋下線
     * @param  date $_startDate   開始時間
     * @param  date $_endDate     結束時間
     * @param  int  $_row         行數
     * @param  int  $_page        頁數
     */
    public function getOrderListCount($_minmemberID, $_memberID, $_downType, $_startDate, $_endDate, $_row, $_page){
        return DB::select($this->contStr. "EXEC SSP_ShopOrderListCount
                                            @_minmemberID=?, @_memberID=?, @_downType=?, @_startDate=?, @_endDate=?, @_row=?, @_page=?",
            array($_minmemberID, $_memberID, $_downType, $_startDate, $_endDate, $_row, $_page));
    }

    /**
     * 後台取得會員訂單清單-取資料
     * @param  int  $_minmemberID 搜尋者的編號
     * @param  int  $_memberID    被搜尋者的編號
     * @param  int  $_downType    是否搜尋下線
     * @param  date $_startDate   開始時間
     * @param  date $_endDate     結束時間
     * @param  int  $_row         行數
     * @param  int  $_page        頁數
     */
    public function getOrderListSearch($_minmemberID, $_memberID, $_downType, $_startDate, $_endDate, $_row, $_page){
        return DB::select($this->contStr. "EXEC SSP_ShopOrderListSearch
                                            @_minmemberID=?, @_memberID=?, @_downType=?, @_startDate=?, @_endDate=?, @_row=?, @_page=?",
            array($_minmemberID, $_memberID, $_downType, $_startDate, $_endDate, $_row, $_page));
    }

    /**
     * 後台取得會員藏蛋清單-總數
     * @param  int  $_minmemberID 搜尋者的編號
     * @param  int  $_memberID    被搜尋者的編號
     * @param  int  $_downType    是否搜尋下線
     * @param  date $_startDate   開始時間
     * @param  date $_endDate     結束時間
     * @param  int  $_row         行數
     * @param  int  $_page        頁數
     */
    public function getRebateListCount($_minmemberID, $_memberID, $_downType, $_startDate, $_endDate, $_row, $_page){
        return DB::select($this->contStr. "EXEC SSP_MemberRebateListCount
                                            @_minmemberID=?, @_memberID=?, @_downType=?, @_startDate=?, @_endDate=?, @_row=?, @_page=?",
            array($_minmemberID, $_memberID, $_downType, $_startDate, $_endDate, $_row, $_page));
    }

    /**
     * 後台取得會員藏蛋清單-取資料
     * @param  int  $_minmemberID 搜尋者的編號
     * @param  int  $_memberID    被搜尋者的編號
     * @param  int  $_downType    是否搜尋下線
     * @param  date $_startDate   開始時間
     * @param  date $_endDate     結束時間
     * @param  int  $_row         行數
     * @param  int  $_page        頁數
     */
    public function getRebateListSearch($_minmemberID, $_memberID, $_downType, $_startDate, $_endDate, $_row, $_page){
        return DB::select($this->contStr. "EXEC SSP_MemberRebateListSearch
                                            @_minmemberID=?, @_memberID=?, @_downType=?, @_startDate=?, @_endDate=?, @_row=?, @_page=?",
            array($_minmemberID, $_memberID, $_downType, $_startDate, $_endDate, $_row, $_page));
    }



}
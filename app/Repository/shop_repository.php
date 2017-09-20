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
     * @param  string   $_phone          電話
     * @param  string   $_address        地址
     * @param  int      $_shopID         商品編號
     * @param  float    $_price          售價
     * @param  float    $_points         積分
     * @param  float    $_transport      運費
     * @param  int      $_quantity       數量
     * @param  string   $_memo           備註
     */
    public function addMemberCommodityOrder($_memberID, $_shopID, $_quantity){
        return DB::select($this->contStr. "EXEC SSP_MemberCommodityOrderAdd @_memberID=?, @_shopID=?, @_quantity=?",
            array($_memberID, $_shopID, $_quantity));
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
}
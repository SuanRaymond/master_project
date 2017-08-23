<?php
namespace App\Repository;

use DB;
//member SQL 輔助
class shop_repository{

    /**
     * 新增會員
     * @param  string    $_title      標題
     * @param  string    $_subTitle   副標
     * @param  int	     $_menuID     分類編號
     * @param  float     $_price      售價
     * @param  float     $_points	  積分
     * @param  float     $_transport  運費
     * @param  int	     $_quantity   數量
     * @param  string    $_style      風格
     * @param  string    $_detail     商品說明
     * @param  string    $_norm		  規格
     * @param  string    $_memo       備註
     */
    public function addShopltem($_title, $_subTitle, $_menuID, $_price, $_points, $_transport, $_quantity, $_style, $_detail, $_norm, $_memo){
        return DB::select("EXEC SSP_ShopAdd @_title=?, @_subTitle=?, @_menuID=?, @_price=?, @_points=?, @_transport=?, @_quantity=?, @_style=?, @_detail=?, @_norm=?, @_memo=?",
                array($_title, $_subTitle, $_menuID, $_price, $_points, $_transport, $_quantity, $_style, $_detail, $_norm, $_memo));
    }

    /**
     * 取MenuID
     */
    public function getMenu(){
        return DB::select("EXEC SSP_ShopMenu", 
            array());
    }

    /**
     * 取商品清單
     * @param  int      $_menuID      menuID
     */
    public function getMenuCommodity($_menuID){
        return DB::select("EXEC SSP_ShopMenuCommodity @_menuID=?", 
            array($_menuID));
    }

        /**
     * 取商品明細
     * @param  int      $_shopID      shopID
     */
    public function getShopltemDetail($_shopID){
        return DB::select("EXEC SSP_ShopltemDetailShopID @_shopID=?", 
            array($_shopID));
    }

    /**
     * 取購物車商品
     * @param  int      $_shopID      shopID
     */
    public function getShopltemCar($_shopID){
        return DB::select("EXEC SSP_ShopltemCarShopID @_shopID=?", 
            array($_shopID));
    }
}

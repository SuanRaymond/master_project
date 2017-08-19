<?php
namespace App\Repository;

use DB;
//member SQL 輔助
class shop_repository{

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
}

<?php
namespace App\Repository;

use DB;
//member SQL 輔助
class shopMenu_repository{

    /**
     * 查詢權限代碼是否存在
     * @param  int $_groupID 權限代碼
     */
    public function getMenuList(){
        return DB::connection('shopSQLsrv')->select("SET NOCOUNT ON; EXEC SSP_ShopMenuList", array());
    }
}

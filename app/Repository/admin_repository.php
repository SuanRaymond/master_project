<?php
namespace App\Repository;

use DB;
//member SQL 輔助
class admin_repository{

    /**
     * 驗證會員登入
     * @param  string $_account 會員帳號
     */
    public function checkLoginBase($_account){
        return DB::select("EXEC SSP_AdminCheckLogin @_account=?", array($_account));
    }

}

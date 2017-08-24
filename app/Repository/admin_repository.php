<?php
namespace App\Repository;

use DB;
//member SQL 輔助
class admin_repository{

    public $contStr = '';

    public function __construct()
    {
        if(strlen(env('DB_HOST')) > 10){
            $this->contStr = 'SET NOCOUNT ON; ';
        }
    }

    /**
     * 驗證會員登入
     * @param  string $_account 會員帳號
     */
    public function checkLoginBase($_account){
        return DB::select($this->contStr. "EXEC SSP_AdminCheckLogin @_account=?", array($_account));
    }

}

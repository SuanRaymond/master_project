<?php
namespace App\Repository;

use DB;
//admin SQL 輔助
class tree_repository{

    public $contStr = '';

    public function __construct()
    {
        if(strlen(env('DB_HOST')) > 10){
            $this->contStr = 'SET NOCOUNT ON; ';
        }
    }

    /**
     * 取得會員麵包屑
     * @param  int $_minMemberID    本身代碼
     * @param  int $_memberID       查詢對象代碼
     */
    public function getUnderLine($_minMemberID, $_memberID){
        return DB::select($this->contStr. "EXEC SSP_TreeUpLineGet @_minmemberID=?, @_memberID=?", array($_minMemberID, $_memberID));
    }

}

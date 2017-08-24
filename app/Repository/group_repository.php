<?php
namespace App\Repository;

use DB;
//member SQL 輔助
class group_repository{

	public $contStr = '';

    public function __construct()
    {
        if(strlen(env('DB_HOST')) > 10){
            $this->contStr = 'SET NOCOUNT ON; ';
        }
    }

    /**
     * 查詢權限代碼是否存在
     * @param  int $_groupID 權限代碼
     */
    public function checkGroupID($_groupID){
        return DB::select($this->contStr. "EXEC SSP_SetingGroup @_groupID = ?", array($_groupID));
    }
}

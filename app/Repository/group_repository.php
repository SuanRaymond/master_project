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

    /**
     * 查詢會員權限代碼
     * @param  int $_memberID       會員代碼
     */
    public function getMemberGroupID($_memberID){
        return DB::select($this->contStr. "EXEC SSP_MemberGroupID @_memberID = ?", array($_memberID));
    }

    /**
     * 查詢對象是否在自己名下
     * @param  int $_minMemberID    本身代碼
     * @param  int $_memberID       查詢對象代碼
     */
    public function checkUnderLine($_minMemberID, $_memberID){
        return DB::select($this->contStr. "EXEC SSP_UnderLineGet @_minmemberID = ?, @_memberID = ?", array($_minMemberID, $_memberID));
    }
}

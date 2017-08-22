<?php
namespace App\Repository;

use DB;
//member SQL 輔助
class member_repository{

    /**
     * 新增會員
     * @param  string    $_account      帳號
     * @param  string    $_name         暱稱
     * @param  string    $_password     密碼
     * @param  string    $_mail         信箱
     * @param  string    $_upMemberID   上層會員
     * @param  string    $_groupID      權限代碼
     */
    public function addMember($_account, $_name, $_password, $_mail, $_upMemberID, $_groupID){
        return DB::select("EXEC SSP_MemberAdd @_account=?, @_name=?, @_password=?, @_mail=?, @_upMemberID=?, @_groupID=?",
                array($_account, $_name, $_password, $_mail, $_upMemberID, $_groupID));
    }

    /**
     * 查詢會員編號
     * @param  string $_account 會員帳號
     */
    public function getMemberID($_account){
        return DB::select("EXEC SSP_MemberMemberID @_account=?", array($_account));
    }

    /**
     * 確認會員編號是否存在
     * @param  int $_memberID 會員編號
     */
    public function checkMemberID($_memberID){
        return DB::select("EXEC SSP_MemberMemberIDCheck @_memberID=?", array($_memberID));
    }

    /**
     * 查詢會員帳號是否重複
     * @param  string $_account 會員帳號
     */
    public function checkAccountRepeat($_account){
        return DB::select("EXEC SSP_MemberCheckAccountRepeat @_account=?", array($_account));
    }

    /**
     * 查詢信箱是否重複
     * @param  string $_mail 信箱
     */
    public function checkMailRepeat($_mail){
        return DB::select("EXEC SSP_MemberCheckMailRepeat @_mail=?", array($_mail));
    }

    /**
     * 驗證會員登入
     * @param  string $_account 會員帳號
     */
    public function checkLoginBase($_account){
        return DB::select("EXEC SSP_MemberCheckLogin @_account=?", array($_account));
    }

    /**
     * 寫入會員登入資料
     * @param  int $_memberID 會員編號
     */
    public function setLoginInfo($_memberID, $_languageID, $_equipmentID, $_token, $_iP){
        return DB::select("EXEC SSP_MemberLoginInfoSet @_memberID=?, @_languageID=?, @_equipmentID=?, @_token=?, @_ip=?",
                    array($_memberID, $_languageID, $_equipmentID, $_token, $_iP));
    }

    /**
     * 修改會員資料   
     * @param  int      $_memberID      會員編號
     * @param  string   $_name          暱稱
     * @param  string   $_mail          信箱
     * @param  string   $_address       地址
     * @param  string   $_birthday      生日
     * @param  int      $_gender        性別
     * @param  int      $_languageID    語言
     * @param  string   $_cardID        卡號
     */
    public function updateMemberDetail($_memberID, $_name, $_mail, $_address, $_birthday, $_gender, $_languageID, $_cardID){
        return DB::select("EXEC SSP_MemberDetailUpdate @_memberID=?, @_name=?, @_mail=?, @_address=?, @_birthday=?, @_gender=?, @_languageID=?, @_cardID=?", 
            array($_memberID, $_name, $_mail, $_address, $_birthday, $_gender, $_languageID, $_cardID));
    }

    /**
     * 修改會員密碼
     * @param  int          $_memberID 會員編號
     * @param  string       $_passwordO 舊密碼
     * @param  string       $_passwordN 新密碼
     */
    public function updatePassword($_memberID, $_passwordO, $_passwordN){
        return DB::select("EXEC SSP_MemberPasswordUpdate @_memberID=?, @_passwordO=?, @_passwordN=?",
            array($_memberID, $_passwordO, $_passwordN));
    }

    /**
     * 增加會員密碼錯誤次數一次
     * @param  int $_memberID 會員編號
     */
    public function addPasswordErrorCount($_memberID){
        return DB::update("EXEC SSP_MemberPasswordErrprCountAdd @_memberID=?", array($_memberID));
    }

    /**
     * 清除會員密碼錯誤次數
     * @param  int $_memberID 會員編號
     */
    public function clearPasswordErrorCount($_memberID){
        return DB::update("EXEC SSP_MemberPasswordErrprCountClear @_memberID=?", array($_memberID));
    }


    /**
     * 驗證會員登入
     * @param  int $_memberID 會員編號
     */
    public function getMemberDetail($_memberID){
        return DB::select("EXEC SSP_MemberDetail @_memberID=?", array($_memberID));
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
    public function addMemberCommodityOrder($_memberID, $_phone, $_address, $_shopID, $_price, $_points, $_transport, $_quantity, $_memo){
        return DB::select("EXEC SSP_MemberCommodityOrderAdd @_memberID=?, @_phone=?, @_address=?, @_shopID=?, @_price=?, @_points=?, @_transport=?, @_quantity=?, @_memo=?",
            array($_memberID, $_phone, $_address, $_shopID, $_price, $_points, $_transport, $_quantity, $_memo));
    }

    /**
     * 修改會員訂單
     * @param  int      $_memberID        會員編號
     * @param  int      $_shoporderID     訂單編號
     * @param  int      $_status          狀態
     */
    public function updateMemberCommodityOrder($_memberID, $_shoporderID, $_status){
        return DB::select("EXEC SSP_MemberCommodityOrderUpdate @_memberID=?, @_shoporderID=?, @_status=?",
            array($_memberID, $_shoporderID, $_status));
    }
}

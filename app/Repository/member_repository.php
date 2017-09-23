<?php
namespace App\Repository;

use DB;
//member SQL 輔助
class member_repository{

    public $contStr = '';

    public function __construct()
    {
        if(strlen(env('DB_HOST')) > 10){
            $this->contStr = 'SET NOCOUNT ON; ';
        }
    }

    /**
     * 新增會員
     * @param  string    $_account      帳號
     * @param  string    $_name         暱稱
     * @param  string    $_password     密碼
     * @param  string    $_mail         信箱
     * @param  string    $_upMemberID   上層會員
     * @param  string    $_groupID      權限代碼
     * @param  string    $_verification 驗證碼
     */
    public function addMember($_account, $_name, $_password, $_mail, $_upMemberID, $_groupID, $_verification){
        return DB::select($this->contStr. $this->contStr. "EXEC SSP_MemberAdd @_account=?, @_name=?, @_password=?, @_mail=?, @_upMemberID=?, @_groupID=?, @_verification=?",
                array($_account, $_name, $_password, $_mail, $_upMemberID, $_groupID, $_verification));
    }

    /**
     * 查詢會員編號
     * @param  string $_account 會員帳號
     */
    public function getMemberID($_account){
        return DB::select($this->contStr. "EXEC SSP_MemberMemberID @_account=?", array($_account));
    }

    /**
     * 查詢會員編號－查詢者與被查詢者
     * @param  string $_mineAccount  查詢者帳號
     * @param  string $_account      被查詢者帳號
     */
    public function getMinAndMemberMemberID($_mineAccount, $_account){
        return DB::select($this->contStr. "EXEC SSP_MinAndMemberMemberID @_mineAccount=?, @_account=?", array($_mineAccount, $_account));
    }

    /**
     * 確認會員編號是否存在
     * @param  int $_memberID 會員編號
     */
    public function checkMemberID($_memberID){
        return DB::select($this->contStr. "EXEC SSP_MemberMemberIDCheck @_memberID=?", array($_memberID));
    }

    /**
     * 查詢會員帳號是否重複
     * @param  string $_account 會員帳號
     */
    public function checkAccountRepeat($_account){
        return DB::select($this->contStr. "EXEC SSP_MemberCheckAccountRepeat @_account=?", array($_account));
    }

    /**
     * 查詢信箱是否重複
     * @param  string $_mail 信箱
     */
    public function checkMailRepeat($_mail){
        return DB::select($this->contStr. "EXEC SSP_MemberCheckMailRepeat @_mail=?", array($_mail));
    }

    /**
     * 驗證會員登入
     * @param  string $_account 會員帳號
     */
    public function checkLoginBase($_account){
        return DB::select($this->contStr. "EXEC SSP_MemberCheckLogin @_account=?", array($_account));
    }

    /**
     * 寫入會員登入資料
     * @param  int $_memberID 會員編號
     */
    public function setLoginInfo($_memberID, $_languageID, $_equipmentID, $_token, $_iP, $_position){
        return DB::select($this->contStr. "EXEC SSP_MemberLoginInfoSet @_memberID=?, @_languageID=?, @_equipmentID=?, @_token=?, @_ip=?, @_position=?",
                    array($_memberID, $_languageID, $_equipmentID, $_token, $_iP, $_position));
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
        return DB::select($this->contStr. "EXEC SSP_MemberDetailUpdate @_memberID=?, @_name=?, @_mail=?, @_address=?, @_birthday=?, @_gender=?, @_languageID=?, @_cardID=?", 
            array($_memberID, $_name, $_mail, $_address, $_birthday, $_gender, $_languageID, $_cardID));
    }

    /**
     * 修改會員密碼
     * @param  int          $_memberID 會員編號
     * @param  string       $_passwordO 舊密碼
     * @param  string       $_passwordN 新密碼
     */
    public function updatePassword($_memberID, $_passwordO, $_passwordN){
        return DB::select($this->contStr. "EXEC SSP_MemberPasswordUpdate @_memberID=?, @_passwordO=?, @_passwordN=?",
            array($_memberID, $_passwordO, $_passwordN));
    }

        /**
     * 修改會員照片
     * @param  int          $_memberID 會員編號
     * @param  string       $_photo     照片
     */
    public function updatePhoto($_memberID, $_photo){
        return DB::select($this->contStr. "EXEC SSP_MemberPhotoUpdate @_memberID=?, @_photo=?",
            array($_memberID, $_photo));
    }

    /**
     * 增加會員密碼錯誤次數一次
     * @param  int $_memberID 會員編號
     */
    public function addPasswordErrorCount($_memberID){
        return DB::update($this->contStr. "EXEC SSP_MemberPasswordErrprCountAdd @_memberID=?", array($_memberID));
    }

    /**
     * 清除會員密碼錯誤次數
     * @param  int $_memberID 會員編號
     */
    public function clearPasswordErrorCount($_memberID){
        return DB::update($this->contStr. "EXEC SSP_MemberPasswordErrprCountClear @_memberID=?", array($_memberID));
    }


    /**
     * 查詢會員詳細資料
     * @param  int $_memberID 會員編號
     */
    public function getMemberDetail($_memberID){
        return DB::select($this->contStr. "EXEC SSP_MemberDetail @_memberID=?", array($_memberID));
    }

    /**
     * 查詢會員簡易資料
     * @param  int $_memberID 會員編號
     */
    public function getMemberDetailSimple($_memberID){
        return DB::select($this->contStr. "EXEC SSP_MemberDetailSimple @_memberID=?", array($_memberID));
    }



    /**
     * 查詢會員清單
     * @param  int $_memberID 會員編號
     * @param  int $_memberID 會員編號
     */
    public function getAccountList($_mineMemberID, $_memberID, $_downType, $_row, $_page){
        return DB::select($this->contStr. "EXEC SSP_AccountListGet @_minmemberID=?, @_memberID=?, @_DownType=?,@_row=?, @_page=?",
                   array($_mineMemberID, $_memberID, $_downType, $_row, $_page));
    }


    /**
     * 驗證驗證碼
     * @param  int $_memberID 會員編號
     */
    public function checkVerification($_memberID){
        return DB::select($this->contStr. "EXEC SSP_MemberVerificationCheck @_memberID=?", array($_memberID));
    }

    /**
     * 更新驗證碼
     * @param  int      $_memberID      會員編號
     * @param  string   $_verification  驗證碼
     */
    public function updateVerification($_memberID, $_verification){
        return DB::select($this->contStr. "EXEC SSP_MemberVerificationUpdate @_memberID=?, @_verification=?", array($_memberID, $_verification));
    }

    /**
     * 清除驗證碼
     * @param  int $_memberID 會員編號
     */
    public function clearVerification($_memberID){
        return DB::select($this->contStr. "EXEC SSP_MemberVerificationClear @_memberID=?", array($_memberID));
    }

    /**
     * 驗證驗時效日期
     * @param  int $_memberID 會員編號
     */
    public function getVerificationDate($_memberID){
        return DB::select($this->contStr. "EXEC SSP_MemberVerificationDate @_memberID=?", array($_memberID));
    }

   /**
     * 更新驗證驗時效日期
     * @param  int      $_memberID      會員編號
     * @param  string   $_verification  驗證碼
     */
    public function updateVerificationDate($_memberID, $_verification){
        return DB::select($this->contStr. "EXEC SSP_MemberVerificationDateUpdate @_memberID=?, @_verification=?", array($_memberID,  $_verification));
    }


    /**
     * 藏蛋返利清單
     * @param  int $_memberID 會員編號
     */
    public function getRebateList($_memberID){
        return DB::select($this->contStr. "EXEC SSP_RebateList @_memberID=?", array($_memberID));
    }

    /**
     * 購買藏蛋返利
     * @param  int $_memberID   會員編號
     * @param  int $_rebateType 購買總類
     */
    public function addRebate($_memberID, $_rebateType){
        return DB::select($this->contStr. "EXEC SSP_RebateAdd @_memberID=?, @_rebateType=?", array($_memberID, $_rebateType));
    }

    /**
     * 今日任務 確認是否有藏蛋
     * @param  int $_memberID 會員編號
     */
    public function getRebateTaskToday($_memberID){
        return DB::select($this->contStr. "EXEC SSP_RebateTaskToday @_memberID=?", array($_memberID));
    }

    /**
     * 今日任務清單
     * @param  int $_memberID 會員編號
     */
    public function getRebateTaskList($_memberID){
        return DB::select($this->contStr. "EXEC SSP_RebateTaskList @_memberID=?", array($_memberID));
    }

    /**
     * 今日簽到
     * @param  int $_memberID 會員編號
     */
    public function checkinRebateTask($_memberID){
        return DB::select($this->contStr. "EXEC SSP_RebateTaskCheckin @_memberID=?", array($_memberID));
    }

    /**
     * 今日刮刮卡
     * @param  int $_memberID 會員編號
     */
    public function getRebateTaskScratchCard($_memberID){
        return DB::select($this->contStr. "EXEC SSP_RebateTaskScratchCard @_memberID=?", array($_memberID));
    }

    /**
     * 寫入刮刮卡 賽果
     * @param  int    $_scratchID 卡號
     * @param  int    $_type      刮刮樂種類
     * @param  string $_result    結果
     */
    public function setRebateTaskScratchCardResult($_scratchID, $_type, $_result){
        return DB::select($this->contStr. "EXEC SSP_RebateTaskScratchCardResult @_scratchID=?, @_type=?, @_result=?", array($_scratchID, $_type, $_result));
    }
}

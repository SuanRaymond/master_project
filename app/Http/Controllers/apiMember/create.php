<?php

namespace App\Http\Controllers\ApiMember;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Repository\member_repository;

use App\Services\connection_services;
use App\Services\api_judge_services;
use App\Services\api_respone_services;
class create extends Controller
{
	//共用參數
    public $system;

    /**
        建立會員
        1、從前端接收POST資訊，需取得：
            A：Params：加密後的資料JSON
            （{"Account":"帳號","Name":"暱稱","Password":"密碼","Mail":"信箱","upMemberID":"上層會員","groupID":"權限代碼"}）
            B：Sign：驗證碼
        2、將資訊經由 entrance （確認資料完整性、驗證、比對）
        3、密碼加密
        4、存入資料庫
        5、輸出完整資料
        {"Result":狀態,"MemberID":"會員編號"}
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
    	$this->system->action = '[judge]';
        $this->system = with(new api_judge_services($this->system))->check(['CMA', 'CPW', 'CMN', 'CMM', 'SMRG', 'SCG', 'SMUG']);
        if($this->system->status != 0){
            with(new api_respone_services())->reAPI($this->system->status, $this->system);
        }

        $this->system->verification = str_random(6);

        $db = with(new member_repository())
                ->addMember($this->system->account, $this->system->name, $this->system->password,
                            $this->system->mail, $this->system->upmemberID, $this->system->groupID, $this->system->verification);

        $this->system->action = '[reorderdata]';
        if(empty($db)){
            with(new api_respone_services())->reAPI(500, $this->system);
        }
        foreach($db as $row){
            $this->system->memberID = $row->memberID;
            if($this->system->memberID < 0){
                with(new api_respone_services())->reAPI(501, $this->system);
            }
        }

        //發送驗證碼
        $msg = '感谢您在 FunMugle 注册会员，您的验证码为『'. $this->system->verification. '』请在登入时输入验证，谢谢。';
        $this->system->postArray   = http_build_query(
            array(
                'username'  => env('SEND_MAIL_ACCOUNT'),
                'password'  => env('SEND_MAIL_PASSWORD'),
                'method'    => 1,
                'sms_msg'   => $msg,
                'phone'     => $this->system->account,
                'send_date' => date('Y/m/d'),
                'hour'      => date('H'),
                'min'       => date('i'),
        ));

        $this->system->result = 0;
        with(new connection_services())->sendHTTP(env('SEND_MAIL_URL'), $this->system->postArray);

    	with(new api_respone_services())->reAPI(0, $this->system);
    }
}

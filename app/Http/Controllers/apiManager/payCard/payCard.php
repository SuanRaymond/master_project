<?php
namespace App\Http\Controllers\apiManager\payCard;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class payCard extends Controller
{
    public $system;

    /**
     * 信用卡回傳資訊
     * @param  string   buysafeno   => params->payCardID    紅陽訂單編號
     * @param  string   web         => params->cardKey      商店代號
     * @param  string   Td          => params->shopID       商家訂單編號
     * @param  int      MN          => params->totalManey   交易金額
     * @param  string   webname     => params->webname      商家網站名稱
     * @param  string   Name        => params->name         消費者姓名
     * @param  string   note1       => params->note1        備註 1
     * @param  string   note2       => params->note2        備註 2
     * @param  int      ApproveCode => params->cardKeyCode  交易授權碼
     * @param  int      Card_NO     => params->cardNo       授權卡號後 4 碼
     * @param  int      SendType    => params->sendType     傳送方式
     * @param  string   errcode     => params->errcode      回覆代碼
     * @param  string   errmsg      => params->errmsg       回覆代碼解釋
     * @param  int      Card_Type   => params->cardType     交易類別
     * @param  string   InvoiceNo   => params->invoiceNo    發票號碼
     * @param  string   ChkValue    => params->sign         交易檢查碼
     * @param  string                  cardKey              本地商店代號
     * @param  string                  sign                 本地自行組裝的交易檢查碼
     */
    public function __construct()
    {
        $this->system                      = (object) array();
        $this->system->params              = (object) array();
        $this->system->params->payCardID   = Request()->get('buysafeno');
        $this->system->params->cardKey     = Request()->get('web');
        $this->system->params->shopID      = Request()->get('Td');
        $this->system->params->totalManey  = Request()->get('MN');
        $this->system->params->webname     = Request()->get('webname');
        $this->system->params->name        = Request()->get('Name');
        $this->system->params->note1       = Request()->get('note1');
        $this->system->params->note2       = Request()->get('note2');
        $this->system->params->cardKeyCode = Request()->get('ApproveCode');
        $this->system->params->cardNo      = Request()->get('Card_NO');
        $this->system->params->sendType    = Request()->get('SendType');
        $this->system->params->errcode     = Request()->get('errcode');
        $this->system->params->errmsg      = Request()->get('errmsg');
        $this->system->params->cardType    = Request()->get('Card_Type');
        $this->system->params->invoiceNo   = Request()->get('InvoiceNo');
        $this->system->params->sign        = Request()->get('ChkValue');


        $this->system->cardKey = env('SEND_CARD_KEY');
        $this->system->sign = strtoupper(sha1(env('SEND_CARD_KEY').
                                              env('SEND_CARD_PAS').
                                              $this->system->params->payCardID.
                                              $this->system->params->totalManey.
                                              $this->system->params->errcode));

        //驗證 商店代號 是否正確
        if($this->system->cardKey != $this->system->params->cardKey){
            //不正確即無視這筆交易，並作紀錄
            return;
        }

        //驗證 Sign 是否正確
        if($this->system->sign != $this->system->params->sign){
            //不正確即無視這筆交易，並作紀錄
            return;
        }
    }

    public function sucess()
    {
        
    }

}


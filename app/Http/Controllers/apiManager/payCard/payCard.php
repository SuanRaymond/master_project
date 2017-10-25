<?php
namespace App\Http\Controllers\apiManager\payCard;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Repository\shop_repository;
use App\Http\Controllers\Controller;

class payCard extends Controller
{
    public $system;

    /**
     * 信用卡回傳資訊
     * @param  string   buysafeno   => params->payCardID    紅陽訂單編號
     * @param  string   web         => params->cardKey      商店代號
     * @param  string   Td          => params->shopID       商家訂單編號
     * @param  int      MN          => params->totalMoney   交易金額
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
        $this->system->params->payID       = Request()->get('Td');
        $this->system->params->totalMoney  = Request()->get('MN');
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
                                              $this->system->params->totalMoney.
                                              $this->system->params->errcode));

        //驗證 商店代號 是否正確
        if($this->system->cardKey != $this->system->params->cardKey){
            $db = with(new shop_repository())->addMemberShopPayLog($this->system->params->payID, 0, $this->system->params->payCardID,
                                                                   $this->system->params->name, $this->system->params->cardKeyCode,
                                                                   $this->system->params->cardNo, $this->system->params->errcode,
                                                                   $this->system->params->errmsg, $this->system->params->invoiceNo, 2);
            return;
        }

        //驗證 Sign 是否正確
        if($this->system->sign != $this->system->params->sign){
            $db = with(new shop_repository())->addMemberShopPayLog($this->system->params->payID, 0, $this->system->params->payCardID,
                                                                   $this->system->params->name, $this->system->params->cardKeyCode,
                                                                   $this->system->params->cardNo, $this->system->params->errcode,
                                                                   $this->system->params->errmsg, $this->system->params->invoiceNo, 3);
            return;
        }

        //驗證 金額 是否正確
        $totalMoney = 0;
        $db = with(new shop_repository())->getMemberShopGet($this->system->params->payID);
        foreach($db as $row){
            $totalMoney = $row->mpPayPrice;
        }
        $totalMoney = str_replace(',', '', rtrim(rtrim(number_format($totalMoney, 4), '0'), '.'));

        if($this->system->params->totalMoney != $totalMoney){
            $db = with(new shop_repository())->addMemberShopPayLog($this->system->params->payID, 0, $this->system->params->payCardID,
                                                                   $this->system->params->name, $this->system->params->cardKeyCode,
                                                                   $this->system->params->cardNo, $this->system->params->errcode,
                                                                   $this->system->params->errmsg, $this->system->params->invoiceNo, 4);
            return;
        }
    }

    public function success()
    {
        $this->system->action = '[judge]';

        $db = with(new shop_repository())->addMemberShopPayLog($this->system->params->payID, 1, $this->system->params->payCardID,
                                                               $this->system->params->name, $this->system->params->cardKeyCode,
                                                               $this->system->params->cardNo, $this->system->params->errcode,
                                                               $this->system->params->errmsg, $this->system->params->invoiceNo, 0);

        //改訂購狀態
        foreach($db as $row){
            with(new shop_repository())->updateMemberCommodityOrder($row->memberID, $row->shopOrderID, 1);
        }
    }


    public function error()
    {
        $this->system->action = '[judge]';

        $db = with(new shop_repository())->addMemberShopPayLog($this->system->params->payID, 0, $this->system->params->payCardID,
                                                               $this->system->params->name, $this->system->params->cardKeyCode,
                                                               $this->system->params->cardNo, $this->system->params->errcode,
                                                               $this->system->params->errmsg, $this->system->params->invoiceNo, 1);
    }

}


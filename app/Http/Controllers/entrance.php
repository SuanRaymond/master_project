<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Services\encrypt_services;
use App\Services\api_respone_services;

class entrance extends Controller
{
    public $system;

    public function __construct()
    {
        $this->system              = (object) array();
        $this->system->contentType = Request()->header('content-type', null);
        $this->system->sendData    = Request()->all();
        $this->system->params      = '';
        $this->system->sign        = '';
        $this->system->function    = Request()->path();
        $this->system->action      = '[entrance]';
        $this->system->serverIp    = ip();
        $this->system->serverIp    = explode(',', $this->system->serverIp);
        $this->system->serverIp[]  = '0.0.0.0';
        $this->system->reKey       = '';
        //是否開啟開發模式
        $this->system->deBugMode    = false;
        if(config('app.debug') == true && env('USETYPE') == 'LOCAL'){
            $this->system->deBugMode = true;
        }
    }

    //基本資料驗證
    public function verification()
    {
//----------------------------------資料傳輸方式驗證----------------------------------//
        $this->system->action       = '[check_sendtype]';
        //form-data
        if(is_null($this->system->contentType) || stripos($this->system->contentType, 'form-data') !== false || stripos($this->system->contentType, 'json') !== false){
            $this->getDataBase();
        }
        //x-www-form-urlencoded
        else if(stripos($this->system->contentType, 'x-www-form-urlencoded') !== false){
            $this->getDataBase();
        }
        //raw
        else if(stripos($this->system->contentType, 'text/plain') !== false){
            $this->system->sendData = Request()->getContent();
            //判斷是否為JSON
            if(!isJson($this->system->sendData)){
                $this->callRespone(1);
            }
            $this->system->sendData = json_decode($this->system->sendData);
            $this->getDataBase();
        }
        //nothing
        else{
            $this->callRespone(2);
        }

//----------------------------------確認表層資料都有送達----------------------------------//
        $this->system->action = '[check_base_form-data]';
    	if(is_null($this->system->params)){
            $this->system->params = '';
            $this->callRespone(3);
    	}
    	if(is_null($this->system->sign)){
            $this->system->sign = '';
            $this->callRespone(4);
    	}
        //IP 綁定金鑰
        // $apiMemberKey = env('API_MEMBER_KEY', []);
        // $apiMemberKey = json_decode($apiMemberKey, true);
        // foreach($this->system->serverIp as $serverIp){
        //     if(isset($apiMemberKey[$serverIp])){
        //         $this->system->reKey = $apiMemberKey[$serverIp];
        //         if($this->system->reKey != ''){
        //             break;
        //         }
        //     }
        // }
        $this->system->reKey = config('app.key');
        if($this->system->reKey == ''){
            $this->callRespone(5);
        }

//----------------------------------資料解密----------------------------------//
        $this->system->action       = '[decode]';
        if(!$this->system->deBugMode){
            $encrypt_service = new encrypt_services($this->system->reKey);
            $this->system->paramsStatus = $encrypt_service->LaravelDecode($this->system->params);
            //資料無法解密
            if(is_null($this->system->paramsStatus) || $this->system->paramsStatus == false){
                $this->callRespone(6);
            }
            $this->system->params = $this->system->paramsStatus;
            unset($this->system->paramsStatus);
    		//驗證碼有問題
            $this->system->action = '[decode_check_Sign]';
    		if(!$encrypt_service->DeSign($this->system->sign, $this->system->params)){
                $this->callRespone(7);
    		}
        }

//----------------------------------資料轉換為JSON物件----------------------------------//
		$this->system->action = '[tojson]';
        //判斷是否為JSON
        if(!isJson($this->system->params)){
            $this->callRespone(8);
        }
        $this->system->params = json_decode($this->system->params);

        //資料KEY轉換為小寫，並強制合併
        $this->system = (object) array_merge((array) $this->system, (array) reSetKey($this->system->params));
        unset($this->system->params, $this->system->sendData, $this->system->sign, $this->system->contentType);

        return $this->system;
    }

    /**
     * 取得資料
     */
    public function getDataBase()
    {
        $this->system->sendData  = reSetKey($this->system->sendData);
        $this->system->params    = str_replace(' ', '+', getNull($this->system->sendData, 'params'));
        $this->system->sign      = str_replace(' ', '+', getNull($this->system->sendData, 'sign'));
        if($this->system->deBugMode){
            $this->system->params    = getNull($this->system->sendData, 'params');
            $this->system->sign      = getNull($this->system->sendData, 'sign');
        }
    }

    /**
     * 呼叫輸出函數
     * @param  int $_errorCode 錯誤代碼
     */
    public function callRespone($_errorCode)
    {
        with(new api_respone_services())->reAPI($_errorCode, $this->system);
    }
}

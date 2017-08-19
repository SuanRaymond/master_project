<?php
namespace App\Services;

class connection_services{

	/**
	 * API 送資料給 API
	 * @param  object $_system 資料物件
	 */
	public function callApi($_system)
	{
		//初始化加密方法
        $encrypt_services = new encrypt_services($_system->reKey);
		//轉換資料為JSON
		$_system->sendParams = json_encode($_system->sendParams);
		$_system->sign       = $_system->sendParams;
        if(!$_system->deBugMode){
			$_system->sendParams = $encrypt_services->LaravelEncode($_system->sendParams);
			$_system->sign       = $encrypt_services->EnSign($_system->sign);
        }
        //資料加密與打包
        $_system->Post_Array   = http_build_query(
            array(
                'Params' => $_system->sendParams,
                'Sign'   => $_system->sign
        ));

         //送出
        return $this->sendHTTP($_system->sendApiUrl[0]. '/'. $_system->callFunction, $_system->Post_Array);
	}

	/**
	 * 跨網域送出POST
	 */
	public function sendHTTP($Url, $Post_Array){
		$OutData = '';
		try{
			//初始化
		    $Curl = curl_init();
		    $TimeOut = 30;
		    //設定抓取網址
		    curl_setopt($Curl, CURLOPT_URL, $Url);
		    //POST 啟用
		    curl_setopt($Curl, CURLOPT_POST, true);
		    //POST 字串
		    curl_setopt($Curl, CURLOPT_POSTFIELDS, $Post_Array);
		    //逾時時間
		    curl_setopt($Curl, CURLOPT_CONNECTTIMEOUT, $TimeOut);
		  	//是否回傳
		  	curl_setopt($Curl, CURLOPT_RETURNTRANSFER, true);
		  	//加上 agent 不然反代不回你
		  	curl_setopt($Curl, CURLOPT_USERAGENT, "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_10_5) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/49.0.2623.110 Safari/537.36");
		  	//抓取網頁
		    $OutData = curl_exec($Curl);
		    curl_close($Curl);
		}
		catch (Exception $e) {
			$OutData = json_encode(['result'=>'99']);
		}
		return $OutData;
	}
}
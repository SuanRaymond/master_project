<?php
if(!function_exists('ip')){
    /**
     * 取得反向代理ip
     */
    function ip(){
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $cip = $_SERVER["HTTP_CLIENT_IP"];
        }else if(!empty($_SERVER["HTTP_X_FORWARDED_FOR"])){
            $cip = $_SERVER["HTTP_X_FORWARDED_FOR"];
        }else if(!empty($_SERVER["REMOTE_ADDR"])){
            $cip = $_SERVER["REMOTE_ADDR"];
        }else{
            $cip = "error!";
        }
        $cip = str_replace(', 192.168.21.200', '', $cip);
        $cip = str_replace('192.168.21.200 ,', '', $cip);
        $cip = str_replace('192.168.21.200', '', $cip);
        return $cip;
    }
}

if(!function_exists('browser')){
    /**
     * 取得瀏覽器類型
     */
    function browser(){
        $agent = request()->server('HTTP_USER_AGENT');
        /*
        400 Chrome
        401 Safari
        402 Firefox
        403 Opera
        404 360SE
        405 搜狗
        406 騰訊
        407 世界之窗
        408 遨遊
        409 UC
        410 Avant
        411 IE.6
        412 IE.7
        413 IE.8
        418 EDGE
        420 IE 類別
        499 其他
        */
        if(stripos($agent, "Chrome") || (stripos($agent, "android") && stripos($agent, "linux") && stripos($agent, "mobile safari"))){
            $agent = 400;
        }
        else if(stripos($agent, "safari") && stripos($agent, "version")){
            $agent = 401;
        }
        else if(stripos($agent, "Firefox")){
            $agent = 402;
        }
        else if(stripos($agent, "opera")){
            $agent = 403;
        }
        else if(stripos($agent, "360SE")){
            $agent = 404;
        }
        else if(stripos($agent, "SE") && stripos($agent, "MetaSr")){
            $agent = 405;
        }
        else if(stripos($agent, "TencentTraveler") || stripos($agent, "QQBrowser")){
            $agent = 406;
        }
        else if(stripos($agent, "The world")){
            $agent = 407;
        }
        else if(stripos($agent, "Maxthon")){
            $agent = 408;
        }
        else if(stripos($agent, "UCWEB")){
            $agent = 409;
        }
        else if(stripos($agent, "Avant")){
            $agent = 410;
        }
        else if(stripos($agent, "MSIE 6.0") || stripos($agent, "IEMobile")){
            $agent = 411;
        }
        else if(stripos($agent, "MSIE 7.0") || stripos($agent, "IEMobile")){
            $agent = 412;
        }
        else if(stripos($agent, "MSIE 8.0") || stripos($agent, "IEMobile")){
            $agent = 413;
        }
        else if(stripos($agent, "MSIE") || stripos($agent, "IEMobile")){
            $agent = 420;
        }
        else if(stripos($agent, "EDGE")){
            $agent = 418;
        }
        else{
            $agent = 499;
        }

        return $agent;
    }
}

if(!function_exists('isJson')){
    /**
     * 判斷是否為JSon
     * @param  string  $_json Json  字串
     * @return boolean              true：是、false：否
     */
    function isJson($_json){
        json_decode($_json);
        return json_last_error() == JSON_ERROR_NONE;
    }
}

if(!function_exists('getNull')){
    /**
     * 如果抓取得值不存在預設NULL
     * @param  object  $_object     物件 / 陣列
     * @param  string  $_key        Key
     * @return value                存在：值、不存在：NULL
     */
    function getNull($_object, $_key){
        $outData = null;
        if(isset($_object->$_key)){
            $outData = $_object->$_key;
        }
        else if(isset($_object[$_key])){
            $outData = $_object[$_key];
        }
        return $outData;
    }
}

if(!function_exists('reSetKey')){
    /**
     * 重新設定物件/陣列的KEY（全面轉為全小寫）
     * @param  object  $_object     物件 / 陣列
     */
    function reSetKey($_object){
        //輸出用
        $reObject = null;
        $_reCover = ['ID', 'Date', 'Url'];

        //覆蓋參數初始化
        $reCover = [];
        foreach($_reCover as $key){
            $reCover[strtolower($key)] = $key;
        }

        //確認使用方法
        if(gettype($_object) === 'object'){
            $reObject = (object) array();
        }
        else if(gettype($_object) === 'array'){
            $reObject = [];
        }

        //轉換
        foreach($_object as $key => $value){
            $reKey = strtolower($key);

            //替換部分KEY
            foreach($_reCover as $reCoverKey){
                $reKey = str_replace(strtolower($reCoverKey), $reCoverKey, $reKey);
            }

            if(gettype($_object) === 'object'){
                $reObject->$reKey = trim($value);
            }
            else if(gettype($_object) === 'array'){
                $reObject[$reKey] = trim($value);
            }
        }
        return $reObject;
    }
}


?>
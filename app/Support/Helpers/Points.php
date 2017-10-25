<?php

if(!function_exists('pFormat')){
    /**
     * 數字格式化
     */
    function pFormat($_num){
        return rtrim(rtrim(number_format($_num, 4), '0'), '.');
    }
}

if(!function_exists('pRFormat')){
    /**
     * 數字反格式化
     */
    function pRFormat($_num){
        return str_replace(',', '', $_num);
    }
}

?>
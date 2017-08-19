<?php
namespace App\Services;

use Illuminate\Contracts\Encryption\DecryptException;
class encrypt_services{
	public $key;

	public function __construct($_key = '')
    {
		$this->key = $_key;
    }

	//Laravel 加密
    public function LaravelEncode($_values)
	{
		config(['app.key' => $this->key]);
		$data = null;
        try {
        	$data = encrypt($_values);
	    }
	    catch(\Exception $e){
	        //Laravel try catch 要使用這種catch(\Exception $e)
			// dd($e);
	    }
	    return $data;
    }

    //Laravel 解密
    public function LaravelDecode($_values)
	{
		config(['app.key' => $this->key]);
		$data = null;
        try {
        	$data = decrypt($_values);
	    }
	    catch(\Exception $e){
	        //Laravel try catch 要使用這種catch(\Exception $e)
			// dd($e);
	    }
	    return $data;
    }

    //Sign 加密
    public function EnSign($_values)
	{
		$data = null;
        try {
        	$data = md5(date('Ymd'). $_values. date('Hi'));
	    }
	    catch(\Exception $e){
	        //Laravel try catch 要使用這種catch(\Exception $e)
			// dd($e);
	    }
	    return $data;
    }

    //Sign 比對
    public function DeSign($_sign, $_values)
	{
		$data = false;
        try {
        	$dataArray= [];
        	for($x=0; $x <= 10; $x++){
        		$dataArray[] = md5(date('Ymd'). $_values. date('Hi', mktime(date("H"), date("i") - $x)));
        	}
        	if(in_array($_sign, $dataArray)){
                $data = true;
            }
	    }
	    catch(\Exception $e){
	        //Laravel try catch 要使用這種catch(\Exception $e)
			// dd($e);
	    }
	    return $data;
    }
}

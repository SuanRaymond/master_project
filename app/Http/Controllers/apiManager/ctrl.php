<?php

namespace App\Http\Controllers\apiManager;

use App\Http\Controllers\Controller;
use App\Http\Controllers\entrance;

use App\Services\api_judge_services;
use App\Services\api_respone_services;
class ctrl extends Controller
{
	//共用參數
    public $system;

    /**
        後台取得控制項資訊
     */

    public function __construct()
    {
        $this->system = with(new entrance())->verification();
    }

    public function index()
    {
        $this->system->data = (object) array();
        with(new api_respone_services())->reAPI(0, $this->system);
    }
}

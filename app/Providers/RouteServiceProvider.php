<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    /**
     * This namespace is applied to your controller routes.
     *
     * In addition, it is set as the URL generator's root namespace.
     *
     * @var string
     */
    protected $apiMemberNameSpace = 'App\Http\Controllers\apiMember';
    protected $apiIndexNameSpace  = 'App\Http\Controllers\apiIndex';
    protected $apiShopNameSpace   = 'App\Http\Controllers\apiShop';
    protected $apiMemberDomain    = 'localhost';
    protected $apiIndexDomain     = 'localhost';
    protected $apiShopDomain      = 'localhost';

    /**
     * Define your route model bindings, pattern filters, etc.
     *
     * @return void
     */
    public function boot()
    {
        //

        parent::boot();
    }

    /**
     * Define the routes for the application.
     *
     * @return void
     */
    public function map()
    {
        // 將Domin JSON 轉換為物件
        $this->apiMemberDomain = config('app.urlMemberApi');
        $this->apiMemberDomain = json_decode($this->apiMemberDomain, true);
        $this->apiIndexDomain  = config('app.urlIndexApi');
        $this->apiIndexDomain  = json_decode($this->apiIndexDomain, true);
        $this->apiShopDomain   = config('app.urlShopApi');
        $this->apiShopDomain   = json_decode($this->apiShopDomain, true);

        //是否偽造 Domain
        if(empty($_SERVER['HTTP_HOST'])){
            $this->apiMemberDomain = 'localhost';
            $this->apiIndexDomain  = 'localhost';
            $this->apiShopDomain   = 'localhost';
        }
        //ENV 是否設定 Domain 完成
        else if(is_null($this->apiMemberDomain)){
            $this->apiMemberDomain = 'localhost';
        }
        else if(is_null($this->apiIndexDomain)){
            $this->apiIndexDomain = 'localhost';
        }
        else if(is_null($this->apiShopDomain)){
            $this->apiShopDomain = 'localhost';
        }
        else{
            //取得目前請求者的Domain
            $host = explode(":", $_SERVER['HTTP_HOST'])[0];
            //判斷目前請求者的Domain 是否在允許清單內
            if(in_array($host, $this->apiMemberDomain)){
                $this->mapApiMemberRoutes();
            }
            else if(in_array($host, $this->apiIndexDomain)){
                $this->mapApiIndexRoutes();
            }
            else if(in_array($host, $this->apiShopDomain)){
                $this->mapApiShopRoutes();
            }
            else{
                abort(404);
            }
        }
    }

    /**
     * Member 使用的 Route Group
     */
    protected function mapApiMemberRoutes()
    {
        //prefix('api')-> //http://api.dev/「api」
        Route::middleware('api')
             ->namespace($this->apiMemberNameSpace)
             ->group(base_path('routes/apiMember.php'));
    }

    /**
     * Index 使用的 Route Group
     */
    protected function mapApiIndexRoutes()
    {
        Route::middleware('api')
             ->namespace($this->apiIndexNameSpace)
             ->group(base_path('routes/apiIndex.php'));
    }

    /**
     * Shop 使用的 Route Group
     */
    protected function mapApiShopRoutes()
    {
        Route::middleware('api')
             ->namespace($this->apiShopNameSpace)
             ->group(base_path('routes/apiShop.php'));
    }
}

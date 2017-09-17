<?php
namespace App\Repository;

use DB;
//commodity SQL 輔助
class commodity_repository{

    public $contStr = '';

    public function __construct()
    {
        if(strlen(env('DB_HOST')) > 10){
            $this->contStr = 'SET NOCOUNT ON; ';
        }
    }

    /**
     * 新增商品
     * @param  string    $_title      標題
     * @param  string    $_subTitle   副標
     * @param  int       $_menuID     分類編號
     * @param  float     $_price      售價
     * @param  float     $_points     積分
     * @param  float     $_transport  運費
     * @param  int       $_quantity   數量
     * @param  string    $_style      風格
     * @param  string    $_detail     商品說明
     * @param  string    $_norm       規格
     * @param  string    $_memo       備註
     */
    public function addCommodity($_title, $_subTitle, $_menuID, $_price, $_points, $_transport, $_quantity, $_style, $_detail, $_norm, $_memo){
        return DB::select($this->contStr. "EXEC SSP_ShopAdd @_title=?, @_subTitle=?, @_menuID=?, @_price=?, @_points=?, @_transport=?, @_quantity=?, @_style=?, @_detail=?, @_norm=?, @_memo=?",
                array($_title, $_subTitle, $_menuID, $_price, $_points, $_transport, $_quantity, $_style, $_detail, $_norm, $_memo));
    }

    /**
     * 更新商品
     * @param  int       $_shopID     商品編號
     * @param  string    $_title      標題
     * @param  string    $_subTitle   副標
     * @param  int       $_menuID     分類編號
     * @param  float     $_price      售價
     * @param  float     $_points     積分
     * @param  float     $_transport  運費
     * @param  int       $_quantity   數量
     * @param  string    $_style      風格
     * @param  string    $_detail     商品說明
     * @param  string    $_norm       規格
     * @param  string    $_memo       備註
     */
    public function updateCommodity($_shopID, $_title, $_subTitle, $_menuID, $_price, $_points, $_transport, $_quantity, $_style, $_detail, $_norm, $_memo){
        return DB::select($this->contStr. "EXEC SSP_ShopUpdate @_shopID=?, @_title=?, @_subTitle=?, @_menuID=?, @_price=?, @_points=?, @_transport=?, @_quantity=?, @_style=?, @_detail=?, @_norm=?, @_memo=?",
                array($_shopID, $_title, $_subTitle, $_menuID, $_price, $_points, $_transport, $_quantity, $_style, $_detail, $_norm, $_memo));
    }

    /**
     * 取商品資訊
     * @param  int      $_shopID      商品編號
     */
    public function getCommodity($_shopID){
        return DB::select($this->contStr. "EXEC SSP_ShopGet @_shopID=?",
                array($_shopID));
    }

    /**
     * 新增商品圖片連結
     * @param  int      $_shopID      商品編號
     * @param  int      $_type        圖片種類
     * @param  string   $_Title       圖片名稱
     */
    public function InsertShopImages($_shopID, $_type, $_title){
        return DB::select($this->contStr. "EXEC SSP_ShopInsertImages @_shopID=?, @_type=?, @_title=?",
                array($_shopID, $_type, $_title));
    }

    /**
     * 清除商品圖片連結
     * @param  int      $_shopID      商品編號
     */
    public function ClearShopImages($_shopID){
        return DB::select($this->contStr. "EXEC SSP_ShopClearImages @_shopID=?",
                array($_shopID));
    }
}

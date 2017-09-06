<?php
namespace App\Repository;

use DB;
//images SQL 輔助
class images_repository{

    public $contStr = '';

    public function __construct()
    {
        if(strlen(env('DB_HOST')) > 10){
            $this->contStr = 'SET NOCOUNT ON; ';
        }
    }

    /**
     * 取得圖片資訊
     * @param  string $_title 圖片檔名
     */
    public function getImagesItem($_title){
        return DB::select($this->contStr. "EXEC SSP_ImagesItemGet @_title=?", array($_title));
    }

    /**
     * 上傳圖片資訊
     * @param  string $_title  圖片檔名
     * @param  string $_images 圖片 Base64
     */
    public function insertImagesItem($_title, $_images){
        return DB::select($this->contStr. "EXEC SSP_ImagesItemInsert @_title=?, @_images=?", array($_title, $_images));
    }
}

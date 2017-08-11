<?php
namespace App\Services;

use App\Repository\log_repository;
use App\Repository\member_repository;
class api_respone_services
{

	/**
	 * API 回應
	 * @param int           $_ErrorCode      錯誤代碼
	 * @param object        $_System      	 資料物件
	 */
	public function reAPI($_result, $_system)
	{
		// 回傳 json 資訊
		$OutPut_Array = ['Result' => $_result];

		// 回傳訊息
		if($_result == 0){
			switch($_system->function){
				case 'Key':
					break;
				case 'Login':
				case 'Detail':
					$OutPut_Array['Member'] = $_system->member;
					break;
				case 'Create':
					$OutPut_Array['memberID'] = $_system->memberID;
					break;
				case 'DetailUpdate':
				case 'PasswordUpdate':
					$OutPut_Array['Result'] = $_system->result;
					break;
				case 'SetLoginInfo':
					$OutPut_Array['Result'] = $_system->result;
					$OutPut_Array['Token'] = $_system->token;
					break;
			}
		}
		else{
		}

		/*****************************************************************/
		//LOG 紀錄事項
		//初始化
		$_system->OutPut_Log = [];
		switch($_result){
			case '0':
				switch($_system->function){
					case 'Login':
						break;
					default:
						$_system->OutPut_Log[] = $OutPut_Array;
						break;
				}
				break;
			/**************************************************************************************/
			case '217':
				//Params 資料不是JSON
				break;
			case '303':
				//Params 資料無法解密
				break;
			case '304':
				//Sign 驗證碼有問題
				break;
			case '500':
				//帳號不存在
				break;
			case '501':
				//帳號重複
				break;
			case '521':
				//帳號過長
				break;
			case '522':
				//帳號過短
				break;
			case '523':
				//密碼錯誤
				break;
			case '524':
				//密碼錯誤次數超過6次
				break;
			case '525':
				//帳號禁用
				break;
			case '526':
				//會員編號不是數字
				break;
			case '527':
				//語言編號不是數字
				break;
			case '528':
				//裝置編號不是數字
				break;
			case '529':
				//IP 過長
				break;
			case '530':
				//密碼過長
				break;
			case '531':
				//密碼過短
				break;
			case '550':
				//帳號沒有輸入
				break;
			case '551':
				//會員編號沒有輸入
				break;
			case '552':
				//語言編號沒有輸入
				break;
			case '553':
				//裝置編號沒有輸入
				break;
			case '554':
				//密碼沒有輸入
				break;
			case '901':
				//資料寫入錯誤
				break;
			case '910':
				//資料傳輸方式錯誤
				break;
			case '911':
				//資料傳輸方式錯誤 raw 裡面沒有 JSON
				break;
			case '912':
				//無法取得此訪問IP的金耀
				break;
			case '1013':
				//Params 資料不存在
				break;
			case '1014':
				//Sign 資料不存在
				break;
		}

		// $_system->OutPut_Log = json_encode($_system->OutPut_Log);
		// if($_system->agLoginfo == '0'){
		// 	// 寫全部紀錄
		// 	with(new log_repository())->add($_result, $_system, 'api');
		// }
		// else{
		// 	if($_result != '0'){
		// 		// 只寫失敗紀錄
		// 		with(new log_repository())->add($_result, $_system, 'api');
		// 	}
		// }

		/*****************************************************************/
		//轉換為JSON輸出
		echo json_encode($OutPut_Array);
		exit;

	}
}

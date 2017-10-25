<?php
namespace App\Services;

use App\Repository\group_repository;
use App\Repository\member_repository;
use App\Repository\admin_repository;
use App\Repository\shop_repository;

class api_judge_services{

    public $system;

	public function __construct($_system)
    {
        $this->system = $_system;
    }
	/**
	 * 判斷式模組
	 * @param  object $_system	資料物件
	 * @param  array  $_switch  模組切換
	 * @return string          	0：正確
	 * @return CMA           	確認帳號
	 * @return CPW           	確認密碼
	 * @return CAPI           	確認API 回傳的資料

	 */
	public function check($_switch)
	{
		$this->system->status = 0;
		$member_repository    = new member_repository();
		$admin_repository     = new admin_repository();
		$shop_repository      = new shop_repository();
		$group_repository     = new group_repository();
		foreach($_switch as $key){
			switch($key){
				/************** 確認資訊 **************/
				case 'CMA':
					//帳號是否輸入
			    	if(!isset($this->system->account)){
			    		return $this->respone(120);
			    	}
			    	//帳號是否過長
			    	if(strlen($this->system->account) > 20){
			    		return $this->respone(121);
					}
					//帳號是否過短
					if(strlen($this->system->account) < 6){
						return $this->respone(122);
					}
					//帳號是否數字
			     	if(!is_numeric($this->system->account)){
			     		return $this->respone(123);
			     	}
			     	if(!preg_match("/^([0-9]+)$/", $this->system->account)){
			    		return $this->respone(133);
					}
					break;
				case 'CPW':
					//密碼是否輸入
			    	if(!isset($this->system->password)){
			    		return $this->respone(130);
			    	}
			    	//密碼是否過長
			    	if(strlen($this->system->password) > 20){
			    		return $this->respone(131);
					}
					//密碼是否過短
					if(strlen($this->system->password) < 6){
						return $this->respone(132);
					}
					//密碼是否符合規則
					if(!preg_match("/^([0-9A-Za-z]+)$/", $this->system->password)){
			    		return $this->respone(133);
					}
					break;
				case 'CMN':
					//暱稱是否輸入
			    	if(!isset($this->system->name)){
			    		return $this->respone(140);
			    	}
			    	//暱稱是否過長
			    	if(strlen($this->system->name) > 20){
			    		return $this->respone(141);
					}
					//暱稱是否過短
					if(strlen($this->system->name) < 2){
						return $this->respone(142);
					}
					break;
				case 'CMM':
					//信箱是否輸入
			    	if(!isset($this->system->mail)){
			    		return $this->respone(150);
			    	}
			    	if($this->system->mail != '')
			    	{
			    		//信箱是否過長
				    	if(strlen($this->system->mail) > 50){
				    		return $this->respone(151);
						}
						//信箱是否過短
						if(strlen($this->system->mail) < 3){
							return $this->respone(152);
						}
						//信箱是否符合規則
				    	if(!preg_match("/^[\w]*@[\w-]+(\.[\w-]+)+$/", $this->system->mail)){
				    		return $this->respone(153);
						}
			    	}

					break;

				case 'CMG':
					//權限代碼是否輸入
			    	if(!isset($this->system->groupID)){
			    		return $this->respone(160);
			    	}
					break;

				case 'CMUID':
					//上層帳號代碼是否輸入
			    	if(isset($this->system->upmemberID)){
			    		if(!is_numeric($this->system->upmemberID)){
			    			$this->system->upmemberID = 300101;
			    		}
			    	}
					break;

				case 'CMID':
					//會員唯一碼是否輸入
			    	if(isset($this->system->memberID)){
			    		if(!is_numeric($this->system->memberID)){
			    			return $this->respone(170);
			    		}
			    	}
					break;

				case 'CML':
					//語言是否輸入
			    	if(isset($this->system->languageID)){
			    		if(!is_numeric($this->system->languageID)){
			    			return $this->respone(180);
			    		}
			    	}
					break;

				case 'CME':
					//裝置是否輸入
					if(isset($this->system->equipmentID)){
			    		if(!is_numeric($this->system->equipmentID)){
			    			return $this->respone(190);
			    		}
			    	}
					break;

				case 'CMI':
					//IP是否輸入
					if(!isset($this->system->ip)){
			    			return $this->respone(200);
			    	}
					break;

				case 'CMT':
					//Token是否符合規則
					if(!preg_match("/^[a-zA-Z0-9]{20}$/", $this->system->token)){
			    		return $this->respone(210);
					}
					break;

				case 'CMV':
					//驗證碼是否符合規則
					if(!preg_match("/^[a-zA-Z0-9]{6}$/", $this->system->verification)){
			    		return $this->respone(211);
					}
					break;

				case 'CMAD':
					//地址是否過長
			    	if(strlen($this->system->address) > 50){
			    		return $this->respone(220);
					}
					break;

				case 'CMB':
					//生日是否為數字
			    	if(isset($this->system->birthday)){
			    		if($this->system->birthday != '')
			    		{
			    			if(!preg_match("/^[0-9]{4}-[0-9]{2}-[0-9]{2}$/", $this->system->birthday)){
			    				return $this->respone(230);
			    			}
			    		}
			    	}
					break;

				case 'CMGD':
					//性別是否為數字
			    	if(isset($this->system->gender)){
			    		if(!is_numeric($this->system->gender)){
			    			return $this->respone(240);
			    		}
			    	}
					break;

				case 'CMC':
					//卡號是否為數字
			    	if(isset($this->system->cardID)){
			    		if($this->system->cardID != '')
			    		{
			    			if(!is_numeric($this->system->cardID)){
			    				return $this->respone(250);
			    			}
			    		}
			    	}
					break;

				case 'CPWON':
			        //舊密碼是否輸入
			        if(!isset($this->system->passwordo)){
			            return $this->respone(260);
			        }
			        //舊密碼是否過長
			        if(strlen($this->system->passwordo) > 20){
			            return $this->respone(261);
			        }
			        //舊密碼是否過短
			        if(strlen($this->system->passwordo) < 6){
			            return $this->respone(262);
			        }
			        //舊密碼是否符合規則
			        if(!preg_match("/^([0-9A-Za-z]+)$/", $this->system->passwordo)){
			            return $this->respone(263);
			        }
			        //新密碼是否輸入
			        if(!isset($this->system->passwordn)){
			            return $this->respone(264);
			        }
			        //新密碼是否過長
			        if(strlen($this->system->passwordn) > 20){
			            return $this->respone(265);
			        }
			        //新密碼是否過短
			        if(strlen($this->system->passwordn) < 6){
			            return $this->respone(266);
			        }
			        //新密碼是否符合規則
			        if(!preg_match("/^([0-9A-Za-z]+)$/", $this->system->passwordn)){
			            return $this->respone(267);
			        }
					break;

				case 'CAPI':
					//確認回傳資料是不是空白
					if($this->system->result == ''){
						return $this->respone(270);
					}
					//確認回傳資料是不是 JSON
					if(!isJson($this->system->result)){
						dd($this->system->result);
						return $this->respone(271);
					}
					//將資料轉換為 Object
					$this->system->result = json_decode($this->system->result);
					//比對回傳狀態是否為成功
					if($this->system->result->Result != 0){
            			return $this->respone($this->system->result->Result);
        			}
					break;

				/************** 確認 資料庫 資訊 **************/
				case 'SMG':
					//取得會員資料
			    	$db = $member_repository->checkLoginBase($this->system->account);
					if(empty($db)){
						return $this->respone(300);
					}
					//將資料空白去除
					foreach($db as $row){
						$this->system->member = reSetKey($row);
					}
					break;
				case 'SAG':
					//取得會員資料
			    	$db = $admin_repository->checkLoginBase($this->system->account);
					if(empty($db)){
						return $this->respone(300);
					}
					//將資料空白去除
					foreach($db as $row){
						$this->system->member = reSetKey($row);
					}
					//判斷是不是管理員
					if($this->system->member->sgroupID > 200){
						return $this->respone(301);
					}
					break;

				case 'SMIDG':
					$db = $member_repository->getMemberID($this->system->account);
					if(empty($db)){
						return $this->respone(351);
					}

					foreach($db as $row){
						$this->system->memberID = $row->memberID;
					}
					break;
				case 'SMLC-ID':
					//先取得兩方ID
					$db = $member_repository->getMinAndMemberMemberID($this->system->mineaccount, $this->system->account);
					if(empty($db)){
						//完全沒有
						return $this->respone(351);
					}

					foreach($db as $row){
						$this->system->mineMemberID = $row->Mine;
						$this->system->logMemberID  = $row->Mine;
						$this->system->memberID     = $row->Down;
					}

					if(is_null($this->system->mineMemberID)){
						//找不到搜尋者
						return $this->respone(352);
					}
					if(is_null($this->system->memberID)){
						//找不到被搜尋者
						return $this->respone(353);
					}

					//取得搜尋者的權限
					$db = $group_repository->getMemberGroupID($this->system->mineMemberID);
					if(empty($db)){
						//找不到搜尋者的權限代碼
						return $this->respone(354);
					}

					foreach($db as $row){
						$this->system->mineGroupID = $row->groupID;
					}

					//判斷是不是管理層
					if($this->system->mineGroupID < 200){
						$this->system->mineMemberID = null;
					}
					else{
						//判斷階層
						$db = $group_repository->checkUnderLine($this->system->mineMemberID, $this->system->memberID);
						if(empty($db)){
							//被搜尋者不在搜尋者名下
							return $this->respone(354);
						}
					}

					break;

				case 'SMGD':
					//取得會員詳細資料
			    	$db = $member_repository->getMemberDetail($this->system->memberID);
					if(empty($db)){
						return $this->respone(310);
					}
					//將資料空白去除
					foreach($db as $row){
						$this->system->member = reSetKey($row);
					}
					break;

				case 'SMGDS':
					//取得會員簡易資料
			    	$db = $member_repository->getMemberDetailSimple($this->system->memberID);
					if(empty($db)){
						return $this->respone(311);
					}
					//將資料空白去除
					foreach($db as $row){
						$this->system->member = reSetKey($row);
					}
					break;

				case 'SMRG':
					//查詢會員帳號是否重複
			    	$db = $member_repository->checkAccountRepeat($this->system->account);
					if(!empty($db)){
						$db = $db[0];
						if($db->dataCount != 0){
							return $this->respone(320);
						}
					}
					break;

				case 'SMRM':
					//查詢信箱是否重複
			    	$db = $member_repository->checkMailRepeat($this->system->mail);
					if(!empty($db)){
						$db = $db[0];
						if($db->dataCount != 0){
							return $this->respone(330);
						}
					}
					break;

				case 'SCG':
					//權限代碼是否輸入
					$db = with(new group_repository())->checkGroupID($this->system->groupID);
					if(empty($db)){
						return $this->respone(340);
					}
					$result = false;
					if(!empty($db)){
						foreach($db as $row){
							if($row->dataCount == 0){
								$result = true;
							}
						}
					}
					else{
						$result = true;
					}
					if($result){
						return $this->respone(341);
					}
					break;

				case 'SMUG':
					//上層帳號是否輸入
			    	if(!isset($this->system->upmemberID)){
			    		return $this->respone(350);
			    	}
					//取得會員編號
					if($this->system->upmemberID != ''){
						if ($this->system->upmemberID != 0){
							$db = $member_repository->checkMemberID($this->system->upmemberID);
							if(empty($db)){
								return $this->respone(351);
							}

							foreach($db as $row){
								$this->system->upmemberID = $row->memberID;
							}
						}
					}
					break;

				/************** 後台 **************/

				case 'MCT':
					//標題是否輸入
			    	if(!isset($this->system->title)){
			    		return $this->respone(600);
			    	}
			    	//標題是否輸入
			    	if($this->system->title == ''){
			    		return $this->respone(601);
			    	}
			    	break;
				case 'MCMID':
			    	//menuID是否正確
			    	if(!isset($this->system->menuID)){
			    		return $this->respone(610);
			    	}

			    	$result = false;
			    	$db = $shop_repository->getMenu();
			    	foreach($db as $row){
						if($this->system->menuID == $row->sMenuID){
							$result = true;
						}
					}
			    	if($result == false){
			    		return $this->respone(611);
			    	}
			    	break;
			    case 'MCP':
			    	//售價是否輸入
			    	if(!isset($this->system->price)){
			    		return $this->respone(620);
			    	}
					//售價是否數字
			     	if(!is_numeric($this->system->price)){
			     		return $this->respone(621);
			     	}
			    	break;
			    case 'MCPT':
			    	//點數是否輸入
			    	if(!isset($this->system->points)){
			    		return $this->respone(630);
			    	}
					//點數是否數字
			     	if(!is_numeric($this->system->points)){
			     		return $this->respone(631);
			     	}
			    	break;
			    case 'MCTS':
			    	//運費是否輸入
			    	if(!isset($this->system->transport)){
			    		return $this->respone(640);
			    	}
					//運費是否數字
			     	if(!is_numeric($this->system->transport)){
			     		return $this->respone(641);
			     	}
			    	break;
			    case 'MCQ':
			    	//售價是否輸入
			    	if(!isset($this->system->quantity)){
			    		return $this->respone(650);
			    	}
					//售價是否數字
			     	if(!is_numeric($this->system->quantity)){
			     		return $this->respone(651);
			     	}
			    	break;
			}
		}

		return $this->system;
	}

	public function respone($_status)
	{
		$this->system->status = $_status;
		return $this->system;
	}
}

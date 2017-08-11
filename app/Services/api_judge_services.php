<?php
namespace App\Services;

use App\Repository\group_repository;
use App\Repository\member_repository;

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
		foreach($_switch as $key){
			switch($key){
				/************** 確認資訊 **************/
				case 'CMA':
					//帳號是否輸入
			    	if(!isset($this->system->account)){
			    		return $this->respone(1);
			    	}
			    	//帳號是否過長
			    	if(strlen($this->system->account) > 20){
			    		return $this->respone(2);
					}
					//帳號是否過短
					if(strlen($this->system->account) < 6){
						return $this->respone(3);
					}
					// //帳號是否數字
			  //    	if(!is_numeric($this->system->account)){
			  //    		return $this->respone(333);
			  //    	}
					break;
				case 'CPW':
					//密碼是否輸入
			    	if(!isset($this->system->password)){
			    		return $this->respone(4);
			    	}
			    	//密碼是否過長
			    	if(strlen($this->system->password) > 20){
			    		return $this->respone(5);
					}
					//密碼是否過短
					if(strlen($this->system->password) < 6){
						return $this->respone(6);
					}
					//密碼是否符合規則
					if(!preg_match("/^([0-9A-Za-z]+)$/", $this->system->password)){
			    		return $this->respone(7);
					}
					break;
				case 'CMN':
					//暱稱是否輸入
			    	if(!isset($this->system->name)){
			    		return $this->respone(8);
			    	}
			    	//暱稱是否過長
			    	if(strlen($this->system->name) > 20){
			    		return $this->respone(9);
					}
					//暱稱是否過短
					if(strlen($this->system->name) < 2){
						return $this->respone(10);
					}
					break;
				case 'CMM':
					//信箱是否輸入
			    	if(!isset($this->system->mail)){
			    		return $this->respone(11);
			    	}
			    	//信箱是否過長
			    	if(strlen($this->system->mail) > 50){
			    		return $this->respone(12);
					}
					//信箱是否過短
					if(strlen($this->system->mail) < 3){
						return $this->respone(13);
					}
					//信箱是否符合規則
			    	if(!preg_match("/^[\w]*@[\w-]+(\.[\w-]+)+$/", $this->system->mail)){
			    		return $this->respone(14);
					}
					break;

				case 'CMG':
					//權限代碼是否輸入
			    	if(!isset($this->system->groupID)){
			    		return $this->respone(15);
			    	}
					break;

				case 'CMUID':
					//上層帳號代碼是否輸入
			    	if(isset($this->system->upmemberID)){
			    		if(!is_numeric($this->system->upmemberID)){
			    			$this->system->upmemberID = 0;
			    		}
			    	}
					break;

				case 'CMID':
					//會員唯一碼是否輸入
			    	if(isset($this->system->memberID)){
			    		if(!is_numeric($this->system->memberID)){
			    			return $this->respone(17);
			    		}
			    	}
					break;

				case 'CML':
					//語言是否輸入
			    	if(isset($this->system->languageID)){
			    		if(!is_numeric($this->system->languageID)){
			    			return $this->respone(17);
			    		}
			    	}
					break;

				case 'CME':
					//裝置是否輸入
					if(isset($this->system->equipmentID)){
			    		if(!is_numeric($this->system->equipmentID)){
			    			return $this->respone(19);
			    		}
			    	}
					break;

				case 'CMI':
					//IP是否輸入
					if(!isset($this->system->ip)){
			    			return $this->respone(20);
			    	}
					break;

				case 'CMT':
					//Token是否符合規則
					if(!preg_match("/^[a-zA-Z0-9]{20}$/", $this->system->token)){
			    		return $this->respone(22);
					}
					break;

				case 'CMAD':
					//地址是否過長
			    	if(strlen($this->system->address) > 50){
			    		return $this->respone(23);
					}
					break;

				case 'CMB':
					//生日是否為數字
			    	if(isset($this->system->birthday)){
			    		if(!is_numeric($this->system->birthday)){
			    			return $this->respone(24);
			    		}
			    	}
					break;

				case 'CMGD':
					//性別是否為數字
			    	if(isset($this->system->gender)){
			    		if(!is_numeric($this->system->gender)){
			    			return $this->respone(25);
			    		}
			    	}
					break;

				case 'CMC':
					//卡號是否為數字
			    	if(isset($this->system->cardID)){
			    		if(!is_numeric($this->system->cardID)){
			    			return $this->respone(26);
			    		}
			    	}
					break;

				case 'CPWON':
			        //舊密碼是否輸入
			        if(!isset($this->system->passwordo)){
			            return $this->respone(27);
			        }
			        //舊密碼是否過長
			        if(strlen($this->system->passwordo) > 20){
			            return $this->respone(28);
			        }
			        //舊密碼是否過短
			        if(strlen($this->system->passwordo) < 6){
			            return $this->respone(29);
			        }
			        //舊密碼是否符合規則
			        if(!preg_match("/^([0-9A-Za-z]+)$/", $this->system->passwordo)){
			            return $this->respone(30);
			        }
			        //新密碼是否輸入
			        if(!isset($this->system->passwordn)){
			            return $this->respone(31);
			        }
			        //新密碼是否過長
			        if(strlen($this->system->passwordn) > 20){
			            return $this->respone(32);
			        }
			        //新密碼是否過短
			        if(strlen($this->system->passwordn) < 6){
			            return $this->respone(33);
			        }
			        //新密碼是否符合規則
			        if(!preg_match("/^([0-9A-Za-z]+)$/", $this->system->passwordn)){
			            return $this->respone(34);
			        }
					break;

				case 'CAPI':
					//確認回傳資料是不是空白
					if($this->system->result == ''){
						return $this->respone(100);
					}
					//確認回傳資料是不是 JSON
					if(!isJson($this->system->result)){
						return $this->respone(101);
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
						return $this->respone(500);
					}
					//將資料空白去除
					foreach($db as $row){
						$this->system->member = reSetKey($row);
					}	
					break;

				case 'SMGD':
					//取得會員詳細資料
			    	$db = $member_repository->getMemberDetail($this->system->memberID);	    	
					if(empty($db)){
						return $this->respone(501);
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
							return $this->respone(503);
						}
					}
					break;

				case 'SMRM':
					//查詢信箱是否重複
			    	$db = $member_repository->checkMailRepeat($this->system->mail);
					if(!empty($db)){
						$db = $db[0];
						if($db->dataCount != 0){
							return $this->respone(504);
						}
					}
					break;

				case 'SCG':
					//權限代碼是否輸入
			    	if(!isset($this->system->groupID)){
			    		return $this->respone(505);
			    	}
					$db = with(new group_repository())->checkGroupID($this->system->groupID);
					if(empty($db)){
						return $this->respone(506);
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
						return $this->respone(507);
					}
					break;

				case 'SMUG':
					//上層帳號是否輸入
			    	if(!isset($this->system->upmemberID)){
			    		return $this->respone(508);
			    	}
					//取得會員編號
					if($this->system->upmemberID != ''){
						if ($this->system->upmemberID != 0){
							$db = $member_repository->getMemberID($this->system->upmemberID);
							if(empty($db)){
								return $this->respone(509);
							}

							foreach($db as $row){
								$this->system->upmemberID = $row->MemberID;
							}
						}
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

<?php
class YhjAction extends Action {
    protected function _initialize() {		
		$this->isWx();
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') == false && strpos($useragent, 'Windows Phone') == false ){
			header("location:http://www.968816.com.cn/error.html");
		}	
	}
	
	private function isWx(){		
		if(!$_SESSION['unionid'] || !$_SESSION['openid'] || !$_SESSION['state']){
			$tv = $_GET['tv'];
			if($tv){
				$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1ae00a2623048bf1&redirect_uri=http%3a%2f%2fwww.968816.com.cn%2fqzgdwl%2findex.php%3fm%3dlogin%26a%3dindex&response_type=code&scope=snsapi_base&state=yhj%26yhjJfs%26$tv#wechat_redirect";
			}else{
				$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1ae00a2623048bf1&redirect_uri=http%3a%2f%2fwww.968816.com.cn%2fqzgdwl%2findex.php%3fm%3dlogin%26a%3dindex&response_type=code&scope=snsapi_base&state=yhj%26yhjJfs#wechat_redirect";
			}
			header("Location: $url");
		}
	}
		
	function isAcess($unionid){
		$unionidArr = array();
		$unionidArr['unionid'] = $unionid;
	}
    
	public function sjSaveLog($logStr){
		$log = M('Yhj_log');
		$data = array();
		$data['unionid'] = $_SESSION['unionid'];
		$data['create_date'] = date('Y-m-d H:i:s',time());
		$data['log'] = $logStr;
		$log->data($data)->add();
		
	}
	
	public function isBinding(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$unionidArr = array();
		$unionidArr['unionid'] = $unionid;
		$user = M('User');
		$userRow = $user->where($unionidArr)->select();
		if($userRow){
		}else if($unionid){
			$unionidArr['openid'] = $openid;
			$unionidArr['is_binding'] = 0;
			$unionidArr['subscribe_time'] = date('Y-m-d H:i:s',time());
			$user->data($unionidArr)->add();
		}
		$userRow = $user->where(array('unionid'=>$unionid))->select();	
		$this->assign("userRow",$userRow[0]);	
		
		require_once "jssdk.php";
		$jssdk = new JSSDK("wx1ae00a2623048bf1", "adb07a65fdaf8b6d572713aa2d764d71");
		$signPackage = $jssdk->GetSignPackage();
		$this->assign("signPackage",$signPackage);
	}
  
	
	
	
	public function yhjJfs(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$this->assign("unionid",$unionid);
		$this->assign("time",date("Y-m-d H:i:s"));
		$this->assign("timeS",time());

		$this->isBinding();
		//$arrResult = $this->jfs($unionid);
		$this->assign("isHb",0);
		$this->display(C('HOME_DEFAULT_THEME').':yhjJfs');
	}
	
	
	public function jfsAjax(){
		$result = array();
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$payid = $_SESSION['APPPAYNO'];
		
		$this->assign("unionid",$unionid);
		
		
		if(!$unionid){
			$result['code'] = 404;
		}elseif(!$payid){
			$result['code'] = 404;
		}else{		
			$jsfRow = M('Yhj_jfs')->where(array('payid'=>$payid))->select();
			if($jsfRow){				
				$result['code'] = 0;
			}else{
				$arrReturn = array();
				$arrReturn = $this->runByPraise('预存赠送充值卡');	
				$result['name'] = $arrReturn[0];		
				$result['value'] = $arrReturn[2];		
				$result['code'] = 1;
			}
		}		
		echo $this->json($result);
	}
	
	public function jfs($unionid){
		$userRow = M('User')->where(array('unionid'=>$unionid,'is_binding'=>1))->select();
		$wxTime = '';
		$APPPAYNO = '';
		$log_sjRow = '';//获取有效的缴费进入记录
		$logName = '用户节跳转集团微信支付';
		$arrJfs = array();
		$arrResult = array();
		
		$_SESSION['APPPAYNO'] = '';
		
		$log_sjRow = M('Yhj_log')->where(array('unionid'=>$unionid,'log'=>$logName))->max('create_date');
		//echo substr($log_sjRow,0,10);
		if($log_sjRow && substr($log_sjRow,0,10) == '2017-05-06'){
			//$log_sjRow = M('Yhj_log')->where(array('id'=>$log_sjRow))->select();
			//$wxTime = substr($log_sjRow[0]['create_date'],0,10);
			$wxTime = '2017-05-06';
			if(time()-strtotime('2017-05-07 00:00:00') >= 0){
				$wxTime = '2999-05-06';
			}
			//echo $wxTime;
		}else{
			$arrResult['code'] = 0;
			return $arrResult;
		}
		if($userRow){	
			$jfsRow = M('Yhj_jfs')->where(array('unionid'=>$unionid))->select();
			//print_r($jfsRow);
			if($jfsRow){				
				$arrResult['code'] = 0;
				return $arrResult;
			}else{
				$accountNo = $userRow[0]['accountno'];
				//echo $accountNo;
				$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getCharge&accountNo=$accountNo";
				$values = $this->getBoss($TOKEN_URL);
				if($values[0]['attributes']['RETURN-CODE'] == 0) {					
					foreach($values as $value1){
						if(isset($value1['attributes']['MERCHANTID']) && $value1['attributes']['MERCHANTID'] == '910002' && floatval($value1['attributes']['AMOUNT']) >= 1 && $value1['attributes']['PAYTIME'] >= $wxTime) {
							$log_sjRow1 = M('Yhj_jfs')->where(array('payid'=>$value1['attributes']['APPPAYNO']))->select();
							if($log_sjRow1){
								$arrResult['code'] = 0;
								return $arrResult;
							}
							$_SESSION['APPPAYNO'] = $value1['attributes']['APPPAYNO'];	
							$arrResult['APPPAYNO'] = $value1['attributes']['APPPAYNO'];	
							$arrResult['code'] = 1;
							$arrReturn = array();
							$arrReturn = $this->runByPraise('用户节赠送');	
							return $arrResult;
							break;
							//update `ai_card` t set t.unionid = null,t.cust_code = null,t.cust_name = null,t.accountno=null  where t.card_no = 35060010501;
						}
					}				
				}
				$arrResult['code'] = 0;
				return $arrResult;
			}			
		}		
		$arrResult['code'] = 0;
		return $arrResult;
		
	}
	
	public function getBoss($param){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $param);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		$result = urldecode($output);
		
		$data = str_replace("gb2312","utf-8",$result);
		$parser = xml_parser_create();//创建解析器
		xml_parse_into_struct($parser, $data, $values, $index);//解析到数组
		xml_parser_free($parser);//释放资源
		return $values;		
	}
	
				
	//吐槽随机抽奖
	public function run($name,$cardno){
		//sleep(1.5);
		$arrPirze = array();
		$prize=M('Yhj_cltprize');
		$prizeRow = $prize->order('id asc')->select();		
				
		foreach($prizeRow as $key=>$val){
			if($val['praisecontent'] != $name ){
				$val['chance'] = 0;
			}
			$arrPirze[$key]=$val;
		}
		
		$arr = array();
		$count = array();
		$praiseName = null; //奖品
		$numtimes = 0; //还剩次数
		$angle = 0;		
		$praiseNumber = 0;//所有奖项		
		$unionidArr = array();
		$unionidArr['unionid'] = $_SESSION['unionid'];	
		$unionid = $_SESSION['unionid'];
		$praise = 0;
		$numstore = 0;
		
		foreach ($arrPirze as $key => $val) {
    		$arr[$val['id']] = $val['chance'];
    		$count[$val['id']] = $val['praisenumber'];
		}
		
		$rid = $this->getRand($arr,$count); //根据概率获取奖项id
		$res = $arrPirze[$rid-1]; //中奖项
					
		$mp=M('Cltuserpraise_sj');//保存奖品
		$praise = $res['id'];
		$praiseName = $res['praisename'];
		
		$mpRow = $mp->add(array('unionid'=>$unionid,'prizeid'=>$praise,'praisename'=>$praiseName,'date'=>date("Y-m-d H:i:s"),'isjf'=>$res['isjf'],'ispraise'=>$res['ispraise'],'isexchange'=>0,'number'=>$cardno));
		$angle = 1;
		
		if($mpRow){
			$card_sjRow = M('Card_sj')->where(array('password'=>$cardno))->save(array("status"=>1,"useddate"=>date("Y-m-d H:i:s")));
			if($card_sjRow && $res['ischarge'] == 1){
				M()->startTrans();//开启事务
				$cardArr['unionid']=array('EXP','IS NULL');
				$cardArr['card_value'] = intval($res['value']);//查询条件
				if($name == '幸运红包券充值卡'){
					$cardArr['card_remark'] = '爱家幸运红包';//查询条件
				}elseif($name == '超级红包券充值卡'){
					$cardArr['card_remark'] = '爱家超级红包';//查询条件
				}
				$card = M('Card');
				$idRow = $card->lock(true)->where($cardArr)->min('id');//加锁查询
				if($idRow){
					//执行你想进行的操作, 最后返回操作结果 result
					$user = M('User');
					$userRow = $user->where(array('unionid'=>$unionid))->select();
					$cardRow = $card->where(array('id'=>$idRow))->save(array('unionid'=>$unionid,'cust_code'=>$userRow[0]['cust_code'],'cust_name'=>$userRow[0]['cust_name'],'accountno'=>$userRow[0]['accountno']));
					if(!$cardRow){
						M()->rollback();//回滚
						//$this->error('错误提示');
					}
				}
				M()->commit();//事务提交
			}
			
		}
		
		
		
		//用户抽取的那个奖项减1
		
		$prize = M('Yhj_cltprize');
		$prizeRow = $prize->where(array('id'=>$praise))->select();
		if($prizeRow[0]['praisenumber'] == -1){
			$numstore = -1;
		}else if($prizeRow[0]['praisenumber'] == 0){
			$numstore = 0;
		}else{
			$numstore = $prizeRow[0]['praisenumber']-1;
		}
		$prize->where(array('id'=>$praise))->save(array('praisenumber'=>$numstore));	
		$arrReturn = array();
		$arrReturn[0] = $praise;
		$arrReturn[1] = $praiseName;
		$arrReturn[2] = $res['id'];
		return $arrReturn;		
    }
	
	public function runByPraise($name){
		//sleep(1.5);
		$arrPirze = array();
		$prize=M('Yhj_cltprize');
		$prizeRow = $prize->order('id asc')->select();		
				
		foreach($prizeRow as $key=>$val){
			if($val['praisecontent'] != $name ){
				$val['chance'] = 0;
			}
			$arrPirze[$key]=$val;
		}
		
		$arr = array();
		$count = array();
		$praiseName = null; //奖品
		$numtimes = 0; //还剩次数
		$angle = 0;		
		$praiseNumber = 0;//所有奖项		
		$unionidArr = array();
		$unionidArr['unionid'] = $_SESSION['unionid'];	
		$unionid = $_SESSION['unionid'];
		$praise = 0;
		$numstore = 0;
		
		foreach ($arrPirze as $key => $val) {
    		$arr[$val['id']] = $val['chance'];
    		$count[$val['id']] = $val['praisenumber'];
		}
		
		$rid = $this->getRand($arr,$count); //根据概率获取奖项id
		$res = $arrPirze[$rid-1]; //中奖项
					
		$mp=M('Yhj_cltuserpraise');//保存奖品
		$praise = $res['id'];
		$praiseName = $res['praisename'];
		
		$mpRow = $mp->add(array('unionid'=>$unionid,'prizeid'=>$praise,'praisename'=>$praiseName,'date'=>date("Y-m-d H:i:s"),'isjf'=>$res['isjf'],'ispraise'=>$res['ispraise'],'isexchange'=>0));
		
		M('Yhj_jfs')->add(array('unionid'=>$unionid,'payid'=>$_SESSION['APPPAYNO'],'prizeid'=>$praise,'praisename'=>$praiseName,'date'=>date("Y-m-d H:i:s"),'isjf'=>$res['isjf'],'ispraise'=>$res['ispraise'],'isexchange'=>0));
		
		$angle = 1;
		
		if($mpRow){
			if($res['ischarge'] == 1){
				M()->startTrans();//开启事务
				$cardArr['unionid']=array('EXP','IS NULL');
				$cardArr['card_value'] = intval($res['value']);//查询条件
				$cardArr['card_remark'] = '用户节红包';//查询条件
				$card = M('Card');
				$idRow = $card->lock(true)->where($cardArr)->min('id');//加锁查询
				if($idRow){
					//执行你想进行的操作, 最后返回操作结果 result
					$user = M('User');
					$userRow = $user->where(array('unionid'=>$unionid))->select();
					$cardRow = $card->where(array('id'=>$idRow))->save(array('unionid'=>$unionid,'cust_code'=>$userRow[0]['cust_code'],'cust_name'=>$userRow[0]['cust_name'],'accountno'=>$userRow[0]['accountno']));
					if(!$cardRow){
						M()->rollback();//回滚
						//$this->error('错误提示');
					}
				}
				M()->commit();//事务提交
			}
			
		}
		
		
		
		//用户抽取的那个奖项减1
		
		$prize = M('Yhj_cltprize');
		$prizeRow = $prize->where(array('id'=>$praise))->select();
		if($prizeRow[0]['praisenumber'] == -1){
			$numstore = -1;
		}else if($prizeRow[0]['praisenumber'] == 0){
			$numstore = 0;
		}else{
			$numstore = $prizeRow[0]['praisenumber']-1;
		}
		$prize->where(array('id'=>$praise))->save(array('praisenumber'=>$numstore));	
			
    }
	
	public function runByPraise1(){
		$userMore = M('Custcode_final');	
		$userMoreRow = $userMore->select();
		
		foreach($userMoreRow as $userRow){	
		
				$cardArr['accountno']=array('EXP','IS NULL');
				$cardArr['card_value'] = 60;//查询条件
				$cardArr['card_remark'] = '用户节红包';//查询条件
				$card = M('Card');
				$idRow = $card->where($cardArr)->min('id');//加锁查询
				//echo $idRow;
				if($idRow){
					$cardRow1 = $card->where(array('id'=>$idRow))->save(array('card_status'=>1,'card_useddate'=>date('Y-m-d H:i:s',time()),'cust_code'=>$userRow['cust_code'],'cust_name'=>$userRow['cust_name'],'accountno'=>$userRow['accountno']));
					//执行你想进行的操作, 最后返回操作结果 result
					if($cardRow1){
						$cardRow = $card->where(array('id'=>$idRow))->select();					
						$card_no = $cardRow[0]['card_no'];
						$card_password = $cardRow[0]['card_password'];
						$accountno = $cardRow[0]['accountno'];
						$phone="";
						$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=chargeSure&card_no=$card_no&card_password=$card_password&accountno=$accountno&phone=$phone";
						$values = $this->getBoss($TOKEN_URL);
					}
						
					//echo 	$idRow		;
				}
			
		}
		
		
		echo 123;	
			
    }
	
	public function runByPraise2(){
		
				$cardArr = array();
				$cardArr['accountno']=array('EXP','IS NOT NULL');
				$cardArr['card_status'] = 0;//查询条件0
				$cardArr['card_remark'] = '用户节红包';//查询条件0
				$card = M('Card');
				$idRow = $card->where($cardArr)->select();//加锁查询
				//echo $idRow;
				foreach($idRow as $userRow){
					$card_no = $userRow['card_no'];
					$card_password = $userRow['card_password'];
					$accountno = $userRow['accountno'];
					$phone="";
					$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=chargeSure&card_no=$card_no&card_password=$card_password&accountno=$accountno&phone=$phone";
					$values = $this->getBoss($TOKEN_URL);
					if($values[0]['attributes']['RETURN-CODE'] != "0") {	
					
					}else{					
						$cardRow1 = $card->where(array('id'=>$userRow['id']))->save(array('card_status'=>1,'card_useddate'=>date('Y-m-d H:i:s',time())));
						//执行你想进行的操作, 最后返回操作结果 result
						if($cardRow1){					
							
						}
					}
					
						
					//echo 	$idRow		;
				}
			
			
			
    }
	
    private function getRand($proArr,$proCount){
    	$result = '';
    	$proSum=0;
    	//概率数组的总概率精度  获取库存不为0的
    	foreach($proCount as $key=>$val){
    		if($val==0){
    			continue;
    		}else{
    			$proSum=$proSum+$proArr[$key];
    		}
    	}
    	//概率数组循环
    	foreach ($proArr as $key => $proCur) {
    		if($proCount[$key]==0){
    			continue;
    		}else{
    			$randNum = mt_rand(1, $proSum);//关键
        		if ($randNum <= $proCur) {
        			$result = $key;
           	 		break;
        		}else{
            		$proSum -= $proCur;
        		}
    		}

    	}
    	unset ($proArr);
    	return $result;
    }	
	
	private function json($array){
    	$this->arrayRecursive($array, 'urlencode', true);
		$json = json_encode($array);
		return urldecode($json);
    }
	
	//对数组中所有元素做处理
	private function arrayRecursive(&$array, $function, $apply_to_keys_also = false){
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
			}else{
				$array[$key] = $function($value);
			}
			if ($apply_to_keys_also && is_string($key)){
				$new_key = $function($key);
				if ($new_key != $key){
					$array[$new_key] = $array[$key];
					unset($array[$key]);
				}
			}
		}
	}
	
	function qgsIndex(){	
		//$this->zpSave();
		$voteno = $_GET['voteno'];
		$type = $_GET['type'];
		$card_no = $_GET['card_no'];
		$unionidArr = array();
		$unionidArr['voteno'] = $voteno;	
		if($type == 1){
			$condidate = M('Condidate');
		}else{
			$condidate = M('Condidate_tel');
		}
		$condidateRow = $condidate->where($unionidArr)->select();
			
		$this->assign("condidateRow",$condidateRow[0]);
		$this->assign("type",$type);
		$this->assign("voteno",$voteno);
		require_once "jssdk.php";
		$jssdk = new JSSDK("wx1ae00a2623048bf1", "adb07a65fdaf8b6d572713aa2d764d71");
		$signPackage = $jssdk->GetSignPackage();
		$this->assign("signPackage",$signPackage);
		
		
		$this->display(C('HOME_DEFAULT_THEME').':qgsIndex');
	}
	
}

?>
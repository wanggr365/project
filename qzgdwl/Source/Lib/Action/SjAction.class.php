<?php
class SjAction extends Action {
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
				$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1ae00a2623048bf1&redirect_uri=http%3a%2f%2fwww.968816.com.cn%2fqzgdwl%2findex.php%3fm%3dlogin%26a%3dindex&response_type=code&scope=snsapi_base&state=sj%26sjShareBinding%26$tv#wechat_redirect";
			}else{
				$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1ae00a2623048bf1&redirect_uri=http%3a%2f%2fwww.968816.com.cn%2fqzgdwl%2findex.php%3fm%3dlogin%26a%3dindex&response_type=code&scope=snsapi_base&state=sj%26sjShareBinding#wechat_redirect";
			}
			header("Location: $url");
		}
	}
		
	function isAcess($unionid){
		$unionidArr = array();
		$unionidArr['unionid'] = $unionid;
	}
    
	public function sjSaveLog($logStr){
		$log = M('Log_sj');
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
		
    function sjIndex(){	
	
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$tv = $_GET['tv'];
		if($tv == 1 && $unionid){
			M('Tv')->add(array('unionid'=>$unionid,'create_date'=>date('Y-m-d H:i:s',time())));
		}elseif($tv == 2 && $unionid){
			M('Didi')->add(array('unionid'=>$unionid,'create_date'=>date('Y-m-d H:i:s',time())));
		}elseif($tv == 3 && $unionid){
			M('Hd')->add(array('unionid'=>$unionid,'create_date'=>date('Y-m-d H:i:s',time())));
		}
		
		$this->isBinding();
		
		$this->display(C('HOME_DEFAULT_THEME').':sjHbq');
	}	
	
	public function sjHbq(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$this->assign("unionid",$unionid);
		
		$this->isBinding();
		
		$this->display(C('HOME_DEFAULT_THEME').':sjHbq');
	}
	
	public function sjJfs(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$this->assign("unionid",$unionid);

		$this->isBinding();
		
		$arrResult = $this->jfs($unionid);
		$this->assign("isHb",$arrResult['code']);
		$this->display(C('HOME_DEFAULT_THEME').':sjJfs');
	}
	
	public function sjBinding(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$this->assign("unionid",$unionid);		
		
		$this->isBinding();		
		
		$share_code = M('Share_code');
		$shareCode = "";
		$share_codeRow = $share_code->where(array('unionid'=>$unionid))->select();
		if($share_codeRow){				
			$this->assign("shareCode",$share_codeRow[0]['sharecode']);
			$shareCode = $share_codeRow[0]['sharecode'];
		}else{
			$share_codeRow = $share_code->add(array('unionid'=>$unionid));
			if($share_codeRow){
				$share_id = $share_code->where(array('unionid'=>$unionid))->select();
				$sc = $this->fixZero($share_id[0]['id'],5);
				$share_code->where(array('unionid'=>$unionid))->save(array('sharecode'=>$sc));
				$this->assign("shareCode",$sc);
				$shareCode = $sc;
			}						
		}
		if(!file_exists('QR/'.$shareCode.".png")){

			include 'phpqrcode.php'; 
			$value = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1ae00a2623048bf1&redirect_uri=http%3a%2f%2fwww.968816.com.cn%2fqzgdwl%2findex.php%3fm%3dshareBinding%26a%3dindex&response_type=code&scope=snsapi_base&state=sj%26sjShareBinding%26$unionid%26$shareCode#wechat_redirect";//二维码内容 
			$errorCorrectionLevel = 'L';//容错级别 
			$matrixPointSize = 6;//生成图片大小 
			//生成二维码图片 
			QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2); 
			$logo = 'fjgdwl.jpg';//准备好的logo图片 
			$QR = 'qrcode.png';//已经生成的原始二维码图 
			 
			if ($logo !== FALSE) { 
				$QR = imagecreatefromstring(file_get_contents($QR)); 
				$logo = imagecreatefromstring(file_get_contents($logo)); 
				$QR_width = imagesx($QR);//二维码图片宽度 
				$QR_height = imagesy($QR);//二维码图片高度 
				$logo_width = imagesx($logo);//logo图片宽度 
				$logo_height = imagesy($logo);//logo图片高度 
				$logo_qr_width = $QR_width / 5; 
				$scale = $logo_width/$logo_qr_width; 
				$logo_qr_height = $logo_height/$scale; 
				$from_width = ($QR_width - $logo_qr_width) / 2; 
				//重新组合图片并调整大小 
				imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, 
				$logo_qr_height, $logo_width, $logo_height); 
			} 
			//输出图片 		
			Header("Content-type: image/png");
			ImagePng($QR, 'QR/'.$shareCode.".png");
		}
		$this->display(C('HOME_DEFAULT_THEME').':sjBinding');			
	}
	
	public function sjBindingYwy(){
				
		$share_code = M('Share_code_ywy');
		$shareCode = "";
		$share_codeRow = $share_code->order("id asc")->limit(2000)->select();
		include 'phpqrcode.php'; 
		for($i=0;$i<2000;$i++){
			$shareCode = $this->fixZero($share_codeRow[$i]['id'],5);			
			if(!$share_codeRow[$i]['sharecode']){			
				$share_code->where(array('id'=>$share_codeRow[$i]['id']))->save(array('sharecode'=>$shareCode));
			}
			$value = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1ae00a2623048bf1&redirect_uri=http%3a%2f%2fwww.968816.com.cn%2fqzgdwl%2findex.php%3fm%3dshareBinding%26a%3dindexYwy&response_type=code&scope=snsapi_base&state=sj%26sjShareBinding%26$shareCode#wechat_redirect";//二维码内容 
			$errorCorrectionLevel = 'L';//容错级别 
			$matrixPointSize = 6;//生成图片大小 
			//生成二维码图片 
			QRcode::png($value, 'qrcode.png', $errorCorrectionLevel, $matrixPointSize, 2); 
			$logo = 'fjgdwl.jpg';//准备好的logo图片 
			$QR = 'qrcode.png';//已经生成的原始二维码图 
			 
			if ($logo !== FALSE) { 
				$QR = imagecreatefromstring(file_get_contents($QR)); 
				$logo = imagecreatefromstring(file_get_contents($logo)); 
				$QR_width = imagesx($QR);//二维码图片宽度 
				$QR_height = imagesy($QR);//二维码图片高度 
				$logo_width = imagesx($logo);//logo图片宽度 
				$logo_height = imagesy($logo);//logo图片高度 
				$logo_qr_width = $QR_width / 5; 
				$scale = $logo_width/$logo_qr_width; 
				$logo_qr_height = $logo_height/$scale; 
				$from_width = ($QR_width - $logo_qr_width) / 2; 
				//重新组合图片并调整大小 
				imagecopyresampled($QR, $logo, $from_width, $from_width, 0, 0, $logo_qr_width, 
				$logo_qr_height, $logo_width, $logo_height); 
			} 
			//输出图片 		
			Header("Content-type: image/png");
			ImagePng($QR, 'QRYwy/'.$shareCode.".png");
		}
		
		echo "生成成功";		
	}
	
	public function sjShareBinding(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$this->assign("unionid",$unionid);		
			
		$this->isBinding();			
		
		$this->assign("shareCode",$_GET['code']);
		
		$this->display(C('HOME_DEFAULT_THEME').':sjShareBinding');	
	}
	
	public function sjShareCode(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$this->assign("unionid",$unionid);
		$user = M('User');
		$unionidArr = array();
		$unionidArr['unionid'] = $unionid;
		$userRow = $user->where($unionidArr)->select();
		//$this->sjShareCodeInput();
		if($userRow && $userRow[0]['is_binding'] == 1){
			$this->assign("userRow",$userRow[0]);
			$this->display(C('HOME_DEFAULT_THEME').':sjShareCode');
		}else{			
			$this->display(C('HOME_DEFAULT_THEME').':qzgdwlIndex');
		}
	}
	
	public function sjShareCodeInput(){
		$result = array();
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$this->assign("unionid",$unionid);
		$shareCode = 123;//$_POST['share_code'];
		$share_codeRow = M('Share_code')->where(array('sharecode'=>$shareCode))->select();
		$share_bindingRow = M('Share_binding')->where(array('unionidnext'=>$unionid))->select();
		$userRow = M('User')->where(array('unionid'=>$unionid))->select();
		if($share_codeRow && !$share_bindingRow){
			$row = M('Share_binding')->add(array('unionid'=>$share_codeRow[0]['unionid'],'sharecode'=>$shareCode,'unionidnext'=>$unionid,'cust_code'=>$userRow[0]['cust_code'],'cust_name'=>$userRow[0]['cust_name'],'create_date'=>date('Y-m-d H:i:s',time())));
			if($row){
				$this->bindingShare($share_codeRow[0]['unionid'],$unionid);
				$result['code'] = 1;
				$result['msg'] = '输入正确，红包已存入“优惠券”里';
			}
		}elseif(!$share_codeRow){
			$result['code'] = 0;
			$result['msg'] = '邀请码有误，请输入正确的邀请码';
		}
		echo $this->json($result);
	
	}
	
	public function sjBusiness(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$this->assign("unionid",$unionid);
		
		$this->isBinding();	
		$this->display(C('HOME_DEFAULT_THEME').':sjBusiness');
	}
	
	public function sjRank(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$this->assign("unionid",$unionid);
		
		$this->isBinding();	
		
		$rankRow = M('User')->where(array('is_binding'=>1))->order('score_sj desc')->field('cust_name,score_sj')->limit(10)->select();
		$this->assign("rankRow",$rankRow);
		
		$myRankRow = M('')->query("select * from (select *,(@rang:=@rang+1) as rang from ai_user,(select (@rang:=0)) b  order by cast(score_sj as SIGNED INTEGER) desc ) cs where cs.unionid = '$unionid'");
		$this->assign("myRankRow",$myRankRow[0]);	
		
		$share_bindingRow = M('Share_binding')->where(array('unionid'=>$unionid))->select();
		$this->assign("share_bindingRow",$share_bindingRow);	
		//print_r($myRankRow);
		$this->display(C('HOME_DEFAULT_THEME').':sjRank');
	}
	
	public function bindingShare($unionid,$unionidNext){
		
		
		$prize = M('Cltprize_sj');
		$resRow = $prize->where(array('id'=>11))->select();
		$res = $resRow[0];
		$mpRow = M('Cltuserpraise_sj')->add(array('unionid'=>$unionid,'prizeid'=>$res['id'],'praisename'=>$res['praisename'],'date'=>date("Y-m-d H:i:s"),'isjf'=>$res['isjf'],'ispraise'=>$res['ispraise'],'isexchange'=>0));
		
		$mpNextRow = M('Cltuserpraise_sj')->add(array('unionid'=>$unionidNext,'prizeid'=>$res['id'],'praisename'=>$res['praisename'],'date'=>date("Y-m-d H:i:s"),'isjf'=>$res['isjf'],'ispraise'=>$res['ispraise'],'isexchange'=>0));
		
		$user = M('User');
		$card = M('Card');
		if($mpRow){
			if($res['ischarge'] == 1){
				M()->startTrans();//开启事务
				$cardArr['unionid']=array('EXP','IS NULL');
				$cardArr['card_value'] = intval($res['value']);//查询条件
				$cardArr['card_remark'] = '好友绑定红包';//查询条件				
				$idRow = $card->lock(true)->where($cardArr)->min('id');//加锁查询
				if($idRow){
					//执行你想进行的操作, 最后返回操作结果 result
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
		
		$prizeRow = $prize->where(array('id'=>$res['id']))->select();
		if($prizeRow[0]['praisenumber'] == -1){
			$numstore = -1;
		}else if($prizeRow[0]['praisenumber'] == 0){
			$numstore = 0;
		}else{
			$numstore = $prizeRow[0]['praisenumber']-1;
		}
		$prize->where(array('id'=>$res['id']))->save(array('praisenumber'=>$numstore));
		
		if($mpNextRow){
			if($res['ischarge'] == 1){
				M()->startTrans();//开启事务
				$cardArr['unionid']=array('EXP','IS NULL');
				$cardArr['card_value'] = intval($res['value']);//查询条件
				$cardArr['card_remark'] = '好友绑定红包';//查询条件
				$idRow = $card->lock(true)->where($cardArr)->min('id');//加锁查询
				if($idRow){
					//执行你想进行的操作, 最后返回操作结果 result
					$userRow = $user->where(array('unionid'=>$unionidNext))->select();
					$cardRow = $card->where(array('id'=>$idRow))->save(array('unionid'=>$unionidNext,'cust_code'=>$userRow[0]['cust_code'],'cust_name'=>$userRow[0]['cust_name'],'accountno'=>$userRow[0]['accountno']));
					if(!$cardRow){
						M()->rollback();//回滚
						//$this->error('错误提示');
					}
				}
				M()->commit();//事务提交
			}
			
		}
		
		$prizeRow = $prize->where(array('id'=>$res['id']))->select();
		if($prizeRow[0]['praisenumber'] == -1){
			$numstore = -1;
		}else if($prizeRow[0]['praisenumber'] == 0){
			$numstore = 0;
		}else{
			$numstore = $prizeRow[0]['praisenumber']-1;
		}
		$prize->where(array('id'=>$res['id']))->save(array('praisenumber'=>$numstore));
		
	}
	
	public function fixZero($id,$num){	
		$countId = strlen($id);
		if($countId < $num){
			for($i=$countId;$i<$num;$i++){
				$id = '0'.$id;
			}
		}
		return $id;
		
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
			$jsfRow = M('Jfs')->where(array('payid'=>$payid))->select();
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
		$logName = '双节跳转集团微信支付';
		$arrJfs = array();
		$arrResult = array();
		
		$_SESSION['APPPAYNO'] = '';
		
		$log_sjRow = M('Log_sj')->where(array('unionid'=>$unionid,'log'=>$logName))->min('id');
		if($log_sjRow){
			$log_sjRow = M('Log_sj')->where(array('id'=>$log_sjRow))->select();
			$wxTime = substr($log_sjRow[0]['create_date'],0,10);
			//echo $wxTime;
		}else{
			$arrResult['code'] = 0;
			return $arrResult;
		}
		if($userRow){	
			$jfsRow = M('Jfs')->where(array('unionid'=>$unionid))->select();
			//print_r($jfsRow);
			if($jfsRow){
				foreach($jfsRow as $key => $js){
					$arrJfs[$key] = $js['payid'];
				}
				$accountNo = $userRow[0]['accountno'];
				$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getCharge&accountNo=$accountNo";
				$values = $this->getBoss($TOKEN_URL);
				if($values[0]['attributes']['RETURN-CODE'] == 0) {	
					foreach($values as $value1){
						if(isset($value1['attributes']['OPERATOR']) && $value1['attributes']['OPERATOR'] == 'upp' && floatval($value1['attributes']['AMOUNT']) >= 198 && $value1['attributes']['PAYTIME'] >= $wxTime && !in_array($value1['attributes']['APPPAYNO'],$arrJfs)) {
							
							$_SESSION['APPPAYNO'] = $value1['attributes']['APPPAYNO'];
							$arrResult['APPPAYNO'] = $value1['attributes']['APPPAYNO'];	
							$arrResult['code'] = 1;
							return $arrResult;
							break;
						}
					}			
				}
				$arrResult['code'] = 0;
				return $arrResult;
			}else{
				$accountNo = $userRow[0]['accountno'];
				//echo $accountNo;
				$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getCharge&accountNo=$accountNo";
				$values = $this->getBoss($TOKEN_URL);
				if($values[0]['attributes']['RETURN-CODE'] == 0) {					
					foreach($values as $value1){
						if(isset($value1['attributes']['OPERATOR']) && $value1['attributes']['OPERATOR'] == 'upp' && floatval($value1['attributes']['AMOUNT']) >= 198 && $value1['attributes']['PAYTIME'] >= $wxTime) {
							$_SESSION['APPPAYNO'] = $value1['attributes']['APPPAYNO'];	
							$arrResult['APPPAYNO'] = $value1['attributes']['APPPAYNO'];	
							$arrResult['code'] = 1;
							return $arrResult;
							break;
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
	
	public function hbqShare(){
	
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		
		$result = array();		
		$share = M('Share_sj');
		$shareRow = $share->where(array('unionid'=>$unionid))->select();
		
		if(!$unionid){
			$result['code'] = 404;
			$result['value'] = 0;
		}elseif($shareRow){
			$unionidArr = array();
			$unionidArr['unionid'] = $unionid;
			$unionidArr['create_date'] = date('Y-m-d H:i:s',time());
			$share->data($unionidArr)->add();
			$result['code'] = 0;
			$result['value'] = 0;
		}else{
			$unionidArr = array();
			$unionidArr['unionid'] = $unionid;
			$unionidArr['create_date'] = date('Y-m-d H:i:s',time());
			$share->data($unionidArr)->add();
			$resRow = M('Cltprize_sj')->where(array('id'=>11))->select();
			$res = $resRow[0];
			$mpRow = M('Cltuserpraise_sj')->add(array('unionid'=>$unionid,'prizeid'=>$res['id'],'praisename'=>$res['praisename'],'date'=>date("Y-m-d H:i:s"),'isjf'=>$res['isjf'],'ispraise'=>$res['ispraise'],'isexchange'=>0));
			
			if($mpRow){
				if($res['ischarge'] == 1){
					M()->startTrans();//开启事务
					$cardArr['unionid']=array('EXP','IS NULL');
					$cardArr['card_value'] = intval($res['value']);//查询条件
					$cardArr['card_remark'] = '分享趣味红包';//查询条件
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
			
			$prize = M('Cltprize_sj');
			$prizeRow = $prize->where(array('id'=>$res['id']))->select();
			if($prizeRow[0]['praisenumber'] == -1){
				$numstore = -1;
			}else if($prizeRow[0]['praisenumber'] == 0){
				$numstore = 0;
			}else{
				$numstore = $prizeRow[0]['praisenumber']-1;
			}
			$prize->where(array('id'=>$res['id']))->save(array('praisenumber'=>$numstore));
			$result['code'] = 1;
			$result['value'] = $res['id'];
		}
		echo $this->json($result);		
	}
	
	function sjQRScan(){
		
		$result = array();
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$this->assign("unionid",$unionid);
		$cardno = $_POST['cardno'];
		
		$card_sj = M('Card_sj');
		$card_sjRow = $card_sj->where(array('password'=>$cardno))->select();
		if(!$unionid){
			$result['code'] = 404;
		}elseif($card_sjRow){
			if($card_sjRow[0]['status'] == 0 && $card_sjRow[0]['type'] == '01'){
				$result['code'] = 0;
				$arrReturn = $this->run('幸运红包券充值卡',$cardno);	
				$result['name'] = $arrReturn[0];		
				$result['value'] = $arrReturn[2];		
				$result['type'] = "01";			
			}elseif($card_sjRow[0]['status'] == 0 && $card_sjRow[0]['type'] == '02'){
				$result['code'] = 0;
				$arrReturn = $this->run('超级红包券充值卡',$cardno);	
				$result['name'] = $arrReturn[0];	
				$result['value'] = $arrReturn[2];	
				$result['type'] = "02";							
			}else{
				$result['code'] = 1;
			}
		}else{
			$result['code'] = 2;
		}
		
		echo $this->json($result);
	}
			
	//吐槽随机抽奖
	public function run($name,$cardno){
		//sleep(1.5);
		$arrPirze = array();
		$prize=M('Cltprize_sj');
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
		
		$prize = M('Cltprize_sj');
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
		$prize=M('Cltprize_sj');
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
		
		$mpRow = $mp->add(array('unionid'=>$unionid,'prizeid'=>$praise,'praisename'=>$praiseName,'date'=>date("Y-m-d H:i:s"),'isjf'=>$res['isjf'],'ispraise'=>$res['ispraise'],'isexchange'=>0));
		
		M('Jfs')->add(array('unionid'=>$unionid,'payid'=>$_SESSION['APPPAYNO'],'prizeid'=>$praise,'praisename'=>$praiseName,'date'=>date("Y-m-d H:i:s"),'isjf'=>$res['isjf'],'ispraise'=>$res['ispraise'],'isexchange'=>0));
		
		$angle = 1;
		
		if($mpRow){
			if($res['ischarge'] == 1){
				M()->startTrans();//开启事务
				$cardArr['unionid']=array('EXP','IS NULL');
				$cardArr['card_value'] = intval($res['value']);//查询条件
				$cardArr['card_remark'] = '缴费红包';//查询条件
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
		
		$prize = M('Cltprize_sj');
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
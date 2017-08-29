<?php
class AgentAction extends Action {
    protected function _initialize() {		
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);		
		$this->isWx();
		if(strpos($useragent, 'MicroMessenger') == false && strpos($useragent, 'Windows Phone') == false ){
			//header("location:http://www.968816.com.cn/error.html");
		}		
	}
	
    public function index(){
		Log::write('文件名testsete：' . $tplName);
    	//$this->display(C('HOME_DEFAULT_THEME').':index');
    }
    
    
	
	private function isWx(){
		
		if(!$_SESSION['unionid'] || !$_SESSION['openid'] || !$_SESSION['state']){
			$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1ae00a2623048bf1&redirect_uri=http%3a%2f%2fwww.968816.com.cn%2fqzgdwl%2findex.php%3fm%3dlogin%26a%3dindex&response_type=code&scope=snsapi_base&state=agent%26agentLogin#wechat_redirect";
			header("Location: $url");
		}
	}
	
	private function isLogin(){
		if(!$_SESSION['phone']){			
			$this->display(C('HOME_DEFAULT_THEME').':agentLogin');
			$url = "index.php?m=agent&a=agentLogin";
			header("Location: $url");
		}
	}
	
	public function agentRegister(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$agentUser = M('Agent_user');
		$agentUserRow = $agentUser->getByUnionid($unionid);
		//print_r($agentUserRow);
		if($agentUserRow['confirm'] == 1){			
			$this->display(C('HOME_DEFAULT_THEME').':agentLogin');
		}else{				
			$this->display(C('HOME_DEFAULT_THEME').':agentRegister');
		}
	}
	
	public function agentPassword(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		//$this->agentPsSave();
		$this->display(C('HOME_DEFAULT_THEME').':agentPassword');
	}
	
	public function agentPersonal(){
		$this->isLogin();
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$phone = $_SESSION['phone'];
		$agentUser = M('Agent_user');
		$agentUserRow = $agentUser->getByPhone($phone);		
		$this->assign("agentUserRow",$agentUserRow);
		$this->display(C('HOME_DEFAULT_THEME').':agentPersonal');
	}
	
	public function agentLogout(){		
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$data = array();
		$result = array();
		if($unionid){			
			$data['auto_login'] = 0;
			//$data['confirm'] = 0;
			$agentUser = M('Agent_user');
			$agentUser->where(array('phone'=>$_SESSION['phone']))->save($data);
			$result['code'] = 1;
			$result['msg'] = "即将跳转至登录页面。";
			
			$_SESSION['phone'] = null;
			$_SESSION['org'] = null;
			$_SESSION['org_type'] = null;
			$this->agentSaveLog("注销成功！");
			echo $this->json($result);
		}else{
			$result['code'] = 0;
			$result['msg'] = "操作超时！";
			echo $this->json($result);
		}
	}
	
	public function agentLogin(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$agentUser = M('Agent_user');
		$agentUserRow = $agentUser->getByUnionid($unionid);
		//print_r($agentUserRow);
		if($agentUserRow && $agentUserRow['auto_login'] == 1 && $agentUserRow['confirm'] == 1){			
			$_SESSION['phone'] = $agentUserRow['phone'];
			$_SESSION['org'] = $agentUserRow['org'];
			$_SESSION['org_type'] = $agentUserRow['org_type'];			
			$this->assign("agentUserRow",$agentUserRow);
			$this->agentSaveLog("登录成功！");
			$this->display(C('HOME_DEFAULT_THEME').':agentIndex');
		}else{				
			$this->assign("confirm",$agentUserRow['confirm']);
			$this->display(C('HOME_DEFAULT_THEME').':agentLogin');
		}
	}	
	
	public function agentOrder(){
		$this->isLogin();
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$this->display(C('HOME_DEFAULT_THEME').':agentOrder');
	}
	
	public function agentMoney(){
		$this->isLogin();
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		
		
		// $business = M('Business');
		// $busiRow = $business->order('busi_id desc')->limit(5)->select();
		// $this->assign("busiRow",$busiRow);
		$this->assign("localMonth",strval(date('Y-m',time())));
		$this->assign("localDay",strval(date('Y-m-d',time())));
		$this->assign("localYear",strval(date('Y',time())));
		$this->assign("day",strval(date('d',time())));
		$this->assign("month",strval(date('m',time())));
		
		$this->display(C('HOME_DEFAULT_THEME').':agentMoney');
	}
	
	public function agentOrderList(){
		$this->isLogin();
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		
		
		// $business = M('Business');
		// $busiRow = $business->order('busi_id desc')->limit(5)->select();
		// $this->assign("busiRow",$busiRow);
		$this->assign("unionid",$unionid);
		
		$this->display(C('HOME_DEFAULT_THEME').':agentOrderList');
	}
	
	public function agentBusinessQuery(){
		$startDate = $_POST['startDate'];
		$endDate = $_POST['endDate'].' 23:59:59';
		$result = array();  //array(array('gt',1),array('lt',10)) ;
		if($_SESSION['unionid']){
			$result['code'] = 1;
			$business = M('Agent_business');
			$arrCondition = array();
			$arrCondition['phone'] = $_SESSION['phone'];
			$arrCondition['create_date'] = $startDate == $endDate?array('like',"%$startDate%"):array(array('egt',$startDate),array('elt',$endDate));
			$busiRow = $business->where($arrCondition)->order('busi_id desc')->limit(5)->select();
			$i = 0;
			$sucNum = 0;
			$sucNum1 = 0;
			$sucNum2 = 0;
			$num1 = 0;
			$num2 = 0;
			foreach($busiRow as $busiRow1){
				$result["$i"]['busi_id'] = $busiRow1['busi_id'];
				$result["$i"]['busi_type'] = $busiRow1['busi_type'];
				if(strpos($busiRow1['busi_type'],'68')){
					$num1++;						
					if($busiRow1['busi_status1'] == "已完成" ){					
						$sucNum1++;
					}
				}else{
					$num2++;						
					if($busiRow1['busi_status1'] == "已完成" ){					
						$sucNum2++;
					}
				}
				$result["$i"]['busi_name'] = $busiRow1['busi_name'];
				$result["$i"]['create_date'] = $busiRow1['create_date'];
				$result["$i"]['busi_address'] = $busiRow1['busi_address'];
				$result["$i"]['busi_phone'] = $busiRow1['busi_phone'];	
				$result["$i"]['busi_status1'] = $busiRow1['busi_status1'];
				$result["$i"]['busi_result1'] = $busiRow1['busi_result'];
				$result["$i"]['busi_remark'] = $busiRow1['busi_remark'];	
				$i++;
			}
			$result['num'] = $i;
			$result['sucNum1'] = $sucNum1;
			$result['sucNum2'] = $sucNum2;
			$result['num1'] = $num1;
			$result['num2'] = $num2;
			
		}else{			
			$result['code'] = 0;
		}
		echo $this->json($result);
	}
	
	public function agentMoneyQuery(){
		$startDate = $_POST['startDate'];
		$result = array();  //array(array('gt',1),array('lt',10)) ;
		if($_SESSION['unionid']){
			$result['code'] = 1;
			$business = M('Agent_business');
			$arrCondition = array();
			$arrCondition['phone'] = $_SESSION['phone'];
			$arrCondition['create_date'] = array('like',"%$startDate%");
			$arrCondition['busi_result1'] = '成功';
			$busiRow = $business->where($arrCondition)->order('busi_id desc')->select();
			$i = 0;
			$sucNum1 = 0;
			$sucNum2 = 0;
			$is_exchange = $busiRow[0]['is_exchange'];
			foreach($busiRow as $busiRow1){
				if(strpos($busiRow1['busi_type'],'68')){
					$sucNum1++;
				}else{
					$sucNum2++;
				}
				$i++;
			}
			$result['num'] = $i;
			$result['sucNum1'] = $sucNum1;
			$result['sucNum2'] = $sucNum2;
			$result['is_exchange'] = $is_exchange == 1?"已提取":"未提取";
			
		}else{			
			$result['code'] = 0;
		}
		echo $this->json($result);
	}
	
	public function agentOrderAdd(){
		$business = M('Agent_business');
		$data = array();
		$data['unionid'] = $_SESSION['unionid'];
		$result = array();
		if($_SESSION['unionid']){
			$data['org'] = $_SESSION['org'];
			$data['org_type'] = $_SESSION['org_type'];
			$data['district'] = $_POST['district'];
			$data['town'] = $_POST['town'];
			$data['create_date'] = date('Y-m-d H:i:s',time());
			$data['busi_type'] = substr($_POST['business'],0,10);
			$data['busi_name'] = $_POST['name'];
			$data['busi_phone'] = $_POST['phone'];
			$data['phone'] = $_SESSION['phone'];
			$data['busi_address'] = $_POST['address'];
			$data['busi_status1'] = '待处理';
			$business->data($data)->add();
			$result['code'] = 1;
			$result['msg'] = "提交成功，客服人员将与客户联系。";
			echo $this->json($result);
		}else{
			$result['code'] = 0;
			$result['msg'] = "操作超时！";
			echo $this->json($result);
		}
	}
	
	public function agentRegisterAdd(){		
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$result = array();
		
		$phone = $_POST['phone'];
		$cert_no = $_POST['cert_no'];
		$password = $_POST['password1'];
		$org_type = $_POST['org_type'];
		$verify_no = $_POST['verify_no'];
		$name = $_POST['name'];
		
		$data = array();
		$data['unionid'] = $_SESSION['unionid'];
		$data['openid'] = $openid;
		$data['cert_no'] = $cert_no;
		$data['org_type'] = $org_type;
		$data['password'] = md5($this->post_check($password));
		$data['name'] = $name;
		$data['confirm'] = 1;
		$data['create_date'] = date('Y-m-d H:i:s',time());
		
		if($_SESSION['unionid']){
			$agentUser = M('Agent_user');
			$agentUserRow = $agentUser->getByPhone($phone);
			if($agentUserRow){
				if($agentUserRow['confirm'] == 1){
					$result['code'] = 3;
					$result['msg'] = "该手机号已注册！";
					echo $this->json($result);
				}else{
					require_once "ServerAPI.php";
					$test = new ServerAPI('b71820caab4115163fccea2ebe6a1f6f','f21c592d1317','curl');
					$resultSMS = $test->verifycode($phone,$verify_no);
					if($resultSMS['code'] == 200){
						$result['code'] = 1;
						$agentUser->where(array('phone'=>$phone))->setField($data);
						$result['msg'] = "注册成功";
						$this->agentSaveLog("企业注册成功");
					}else{
						$result['code'] = 3;
						$result['msg'] = "验证失败";
						$this->agentSaveLog($phone."  ".$verify_no."  "."企业注册验证失败");
					}
					echo $this->json($result);
				}
			}else{
				if(strpos($org_type,'企业')){
					$result['code'] = 3;
					$result['msg'] = "您不是受邀请的合作企业代理！";
					echo $this->json($result);
				}else{
					require_once "ServerAPI.php";
					$test = new ServerAPI('b71820caab4115163fccea2ebe6a1f6f','f21c592d1317','curl');
					$resultSMS = $test->verifycode($phone,$verify_no);
					if($resultSMS['code'] == 200){
						$result['code'] = 1;	
						$data['phone'] = $phone;				
						$agentUser->add($data);
						$result['msg'] = "注册成功";
						$this->agentSaveLog("公众注册成功");
					}else{
						$result['code'] = 3;
						$result['msg'] = "验证失败";
						$this->agentSaveLog($phone."  ".$verify_no."  "."公众注册验证失败");
					}
					echo $this->json($result);
				}
			}
		}else{
			$result['code'] = 0;
			$result['msg'] = "操作超时！";
			echo $this->json($result);
		}
	}
	
	public function agentPsSave(){		
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$result = array();
		
		$phone = $_POST['phone'];
		$verify_no = $_POST['verify_no'];
		$password = $_POST['password1'];
		
		$data = array();
		$data['password'] = md5($this->post_check($password));
		
		if($_SESSION['unionid']){
			$agentUser = M('Agent_user');
			$agentUserRow = $agentUser->getByPhone($phone);
			if($agentUserRow){			
					
					require_once "ServerAPI.php";
					$test = new ServerAPI('b71820caab4115163fccea2ebe6a1f6f','f21c592d1317','curl');
					$resultSMS = $test->verifycode($phone,$verify_no);
					if($resultSMS['code'] == 200){
						$result['code'] = 1;
						$data['auto_login'] = 0;
						$agentUser->where(array('phone'=>$phone))->setField($data);
						$result['msg'] = "密码修改成功！";
						$this->agentSaveLog($phone."  密码修改成功！");
					}else{
						$result['code'] = 3;
						$result['msg'] = "验证失败";
						$this->agentSaveLog($phone."  ".$verify_no."  "."密码修改验证失败");
					}
					
					echo $this->json($result);
			}else{
				$result['code'] = 2;
				$result['msg'] = "该手机号尚未注册！";
				echo $this->json($result);
			}
		}else{
			$result['code'] = 0;
			$result['msg'] = "操作超时！";
			echo $this->json($result);
		}		
		
	}
	
	public function agentPsSendSMS(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$result = array();
		
		$phone = $_POST['phone'];
		
		$data = array();
		$data['unionid'] = $_SESSION['unionid'];
		
		if($_SESSION['unionid']){
			$agentUser = M('Agent_user');
			$agentUserRow = $agentUser->getByPhone($phone);
			if($agentUserRow){									
					require_once "ServerAPI.php";
					$test = new ServerAPI('b71820caab4115163fccea2ebe6a1f6f','f21c592d1317','curl');
					$resultSMS = $test->sendSmsCode($phone,'',3055328,6);
					if($resultSMS['code'] == 200){
						$result['code'] = 1;
						$result['msg'] = "短信已发送！";
						$this->agentSaveLog($phone."  修改密码短信已发送");
					}else{
						$result['code'] = 3;
						$result['msg'] = "短信发送失败！";
						$this->agentSaveLog($phone."  修改密码短信发送失败！");
					}
					
					echo $this->json($result);
			}else{
				$result['code'] = 2;
				$result['msg'] = "该手机号尚未注册！";
				echo $this->json($result);
			}
		}else{
			$result['code'] = 0;
			$result['msg'] = "操作超时！";
			echo $this->json($result);
		}	
	}
	
	public function agentSendSMS(){				
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$result = array();
		
		$phone = $_POST['phone'];
		$cert_no = $_POST['cert_no'];
		$password = $_POST['password1'];
		$org_type = $_POST['org_type'];
		
		$data = array();
		$data['unionid'] = $_SESSION['unionid'];
		
		if($_SESSION['unionid']){
			$agentUser = M('Agent_user');
			$agentUserRow = $agentUser->getByPhone($phone);
			if($agentUserRow){
				if($agentUserRow['confirm'] == 1){
					$result['code'] = 3;
					$result['msg'] = "该手机号已注册！";
					echo $this->json($result);
				}else{
					$result['code'] = 1;
					$result['msg'] = "短信已发送！";
					$this->agentSaveLog($phone."  注册短信已发送！");
					echo $this->json($result);
				}
			}else{
				if(strpos($org_type,'企业')){
					$result['code'] = 3;
					$result['msg'] = "您不是受邀请的合作企业代理！";
					echo $this->json($result);
				}else{
					require_once "ServerAPI.php";
					$test = new ServerAPI('b71820caab4115163fccea2ebe6a1f6f','f21c592d1317','curl');
					$resultSMS = $test->sendSmsCode($phone,'',3064106,4);
					if($resultSMS['code'] == 200){
						$result['code'] = 1;
						$result['msg'] = "短信已发送！";
						$this->agentSaveLog($phone."  注册短信已发送！");
					}else{
						$result['code'] = 3;
						$result['msg'] = "短信发送失败！";
						$this->agentSaveLog($phone."  注册短信发送失败！");
					}
					echo $this->json($result);
				}
			}
			
			
			//require_once "ServerAPI.php";
			//$test = new ServerAPI('b71820caab4115163fccea2ebe6a1f6f','f21c592d1317','curl');
			//$test->sendSmsCode($phone,'',3064106,4);
			
			//echo $this->json($result);
		}else{
			$result['code'] = 0;
			$result['msg'] = "操作超时！";
			echo $this->json($result);
		}
	}
	
	public function agentLoginSubmit(){
		$result = array();
		$unionid = $_SESSION['unionid'];
		if($_SESSION['unionid']){
			$data = array();			
			$phone = $_POST['phone'];
			$auto_login = $_POST['auto_login'];
			$password = md5($this->post_check($_POST['password']));
			$agentUser = M('Agent_user');
			$agentUserRow = $agentUser->getByPhone($phone);
			if($agentUserRow && $agentUserRow['confirm'] == 1){
				if ($password == $agentUserRow['password']){
					$_SESSION['phone'] = $agentUserRow['phone'];
					$_SESSION['org'] = $agentUserRow['org'];
					$_SESSION['org_type'] = $agentUserRow['org_type'];		
					$this->assign("agentUserRow",$agentUserRow);
					$result['code'] = 1;
					$result['msg'] = "登录成功！";
					$agentUser->where(array('phone'=>$phone))->setField(array('unionid'=>$unionid,'auto_login'=>$auto_login));
					$this->agentSaveLog("登录成功！");
					echo $this->json($result);
				}else{
					$result['code'] = 2;
					$result['msg'] = "用户名或密码错误！";
					echo $this->json($result);
				}
			}elseif($agentUserRow && $agentUserRow['confirm'] == 0){
				$result['code'] = 2;
				$result['msg'] = "该手机号尚未注册！";
				echo $this->json($result);
			}else{
				$result['code'] = 2;
				$result['msg'] = "用户名或密码错误！";
				echo $this->json($result);
			}
		}else{
			$result['code'] = 0;
			$result['msg'] = "操作超时！";
			echo $this->json($result);
		}
	}
	
	public function agentIndex(){

		if(strpos($_SERVER["HTTP_USER_AGENT"],"Chrome"))  {
			$this->assign("browser","chrome");
		}
		
		$this->assign("unionid",$_SESSION['unionid']);
		$this->assign("openid",$_SESSION['openid']);
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		
		
		$this->display(C('HOME_DEFAULT_THEME').':agentIndex');
	}
	
		  
	private function getJson($url){
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); 
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$output = curl_exec($ch);
		curl_close($ch);
		return json_decode($output, true);
	}
	
	private function https_request($url)
	{
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		$data = curl_exec($curl);
		if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}
		curl_close($curl);
		return $data;
	}
	
	
	
	public function agentSaveLog($logStr){
		$log = M('Agent_log');
		$data = array();
		$data['unionid'] = $_SESSION['unionid'];
		$data['phone'] = $_SESSION['phone'];
		$data['create_date'] = date('Y-m-d H:i:s',time());
		$data['log'] = $logStr;
		$log->data($data)->add();
		
	}
	
	
	
	
	public function post_check($post){     
		if (!get_magic_quotes_gpc()) // 判断magic_quotes_gpc是否为打开     
		{     
			$post = addslashes($post); // 进行magic_quotes_gpc没有打开的情况对提交数据的过滤     
		}     
		$post = str_replace("_", "\_", $post); // 把 '_'过滤掉     
		$post = str_replace("select", "\_", $post); // 把 '_'过滤掉     
		$post = str_replace("insert", "\_", $post); // 把 '_'过滤掉     
		$post = str_replace("update", "\_", $post); // 把 '_'过滤掉     
		$post = str_replace("delete", "\_", $post); // 把 '_'过滤掉     
		$post = str_replace("%", "\%", $post); // 把' % '过滤掉     
		$post = nl2br($post); // 回车转换     
		$post= htmlspecialchars($post); // html标记转换        
		return $post;     
    }   
	
	
	
	public function getBoss($param){
		//$json=file_get_contents($param);
		//$result = urldecode($json);
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
		//print_r($values);
		//echo $values[0]['tag'];
		//echo json_encode($values[1],JSON_UNESCAPED_UNICODE);
		//echo json_decode($values);
		//echo "\n索引数组\n";
		//print_r($index);
		//echo "\n数据数组\n";
		//print_r($values);
		
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
	
	// public function updateMgrAddress(){
		// $userMore = M('User');	
		// $userMoreRow = $userMore->where(array('parentMgrAddrId'=>array('EXP','IS NULL'),'is_binding'=>1))->select();
		// $AddMgr = array();
		// $i = 0;
		// foreach($userMoreRow as $userMoreRow1){
			// $TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=loginBossByName&custcode=".$userMoreRow1['cust_code'];
			// $values = $this->getBoss($TOKEN_URL);
			// if(strlen($values[1]['attributes']['CUSTOMERCODE']) == 12){
				// $AddMgr['parentMgrAddrId'] = $values[1]['attributes']['PARENTMGRADDRID'];	
				// $AddMgr['mgrAddrid'] = $values[1]['attributes']['MGRADDRID'];
				// $userMore->where(array('cust_code'=>$userMoreRow1['cust_code']))->save($AddMgr);	
				// $i++;
			// }
		// }
		// echo $i;
	// }
	public function updateMgrAddress(){
		$userMore = M('User');	
		$userMoreRow = $userMore->where(array('is_binding'=>1))->select();
		$AddMgr = array();
		$i = 0;
		foreach($userMoreRow as $userMoreRow1){
			$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=loginBossByName&custcode=".$userMoreRow1['cust_code'];
			$values = $this->getBoss($TOKEN_URL);
			$AddMgr['parentMgrAddrId'] = $values[1]['attributes']['PARENTMGRADDRID'];	
			$AddMgr['mgrAddrid'] = $values[1]['attributes']['MGRADDRID'];
			if($AddMgr['mgrAddrid'] != $userMoreRow1['mgrAddrid']){		
				$userMore->where(array('cust_code'=>$userMoreRow1['cust_code']))->save($AddMgr);	
				$i++;
			}
		}
		echo $i;
	}


}

?>
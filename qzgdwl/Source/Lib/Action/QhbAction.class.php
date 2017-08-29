<?php
class QhbAction extends Action {
    protected function _initialize() {		
		$this->isWx();
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') == false && strpos($useragent, 'Windows Phone') == false ){
			header("location:http://www.968816.com.cn/error.html");
		}	
	}
	
	private function isWx(){		
		if(!$_SESSION['unionid'] || !$_SESSION['openid'] || !$_SESSION['state']){
				$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1ae00a2623048bf1&redirect_uri=http%3a%2f%2fwww.968816.com.cn%2fqzgdwl%2findex.php%3fm%3dlogin%26a%3dindex&response_type=code&scope=snsapi_base&state=qhb%26qhbIndex#wechat_redirect";			
			header("Location: $url");
		}
	}	
		
    function qhbIndex(){			
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		
		//$this->qhbMoney();
		$unionidArr = array();
		$unionidArr['unionid'] = $unionid;
		$user = M('Qhb_user');
		$userRow = $user->where($unionidArr)->select();
		if($userRow){
			if($userRow[0]['confirm'] == 0){
				$this->display(C('HOME_DEFAULT_THEME').':qhbLogin');
			}else{			
				$this->assign("isStart",$this->accessToken());				
				$this->assign("userRow",$userRow[0]);			
				$this->assign("unionid",$unionid);
				$this->display(C('HOME_DEFAULT_THEME').':qhbIndex');
			}
		}elseif($unionid){
			$unionidArr['openid'] = $openid;
			$unionidArr['subscribe_time'] = date('Y-m-d H:i:s',time());
			$user->data($unionidArr)->add();
			$userRow = $user->where(array('unionid'=>$unionid))->select();	
		}		
	}	
	
	public function qhbMoney(){
		$unionid = $_POST['unionid'];
		//sleep(1.5);
		$result = array();
		$prize=M('Qhb_cltprize');
		$prizeRow = $prize->order('id asc')->select();		
				
		$arr = array();
		$count = array();
		
		foreach ($prizeRow as $key => $val) {
    		$arr[$val['id']] = $val['chance'];
    		$count[$val['id']] = $val['praisenumber'];
		}
		
		$rid = $this->getRand($arr,$count); //根据概率获取奖项id
		$res = $prizeRow[$rid-1]; //中奖项
							
		$mpNextRow = M('Qhb_cltuserpraise')->add(array('unionid'=>$unionid,'prizeid'=>$res['id'],'praisename'=>$res['praisename'],'date'=>date("Y-m-d H:i:s"),'isjf'=>$res['isjf'],'ispraise'=>$res['ispraise'],'isexchange'=>0));
			
		//用户抽取的那个奖项减1

		$prize->where(array('id'=>$res['id']))->setDec('praisenumber',1);	
		$result['value'] = $res['value'];	
		echo $this->json($result);
    }
	
	public function qhbSaveMoney(){		
		//sleep(15);
		$unionid = $_POST['unionid'];
		$number = $_POST['number'];
		$result = array();
		$money = $_POST['money'];
		$result['code'] = M('Qhb_record')->add(array('unionid'=>$unionid,'number'=>$number,'money'=>$money,'create_date'=>date('Y-m-d H:i:s',time())));
		M('Qhb_confirm')->where(array('unionid'=>$unionid))->setInc('money',intval($money));
		M('Qhb_user')->where(array('unionid'=>$unionid))->setDec('times',1);
		echo $this->json($result);		
	}
	
	private function getRand($proArr,$proCount){
    	$result = '';
    	$proSum = 0;
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
	
	function qhbConfirm(){
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$result = array();
		$phone = $_POST['phone'];
		$confirm = M('Qhb_confirm');
		$confirmRow = $confirm->where(array('phone'=>$phone))->select();
		
		if($confirmRow && $confirmRow[0]['confirm'] == 1){
			$result['code'] = 2;
			$result['msg'] = '该手机号已使用';
		}elseif($confirmRow && $confirmRow[0]['confirm'] == 0){		
			$confirm->where(array('phone'=>$phone))->save(array('unionid'=>$unionid,'confirm'=>1,'create_date'=>date("Y-m-d H:i:s")));
			M('Qhb_user')->where(array('unionid'=>$unionid))->save(array('confirm'=>1));
			$result['code'] = 1;
			$result['msg'] = '登记成功';
		}else{
			$result['code'] = 0;
			$result['msg'] = '该手机号不存在';
		}
		echo $this->json($result);
	}
	
	function qhbRecord(){	
		$unionid = $_SESSION['unionid'];
		$openid = $_SESSION['openid'];
		$this->assign("unionid",$unionid);
		
		$rankRow = M('Qhb_confirm')->order('money desc,id asc')->field('username,money')->limit(5)->select();
		$this->assign("rankRow",$rankRow);
		
		$myRankRow = M('')->query("select * from (select *,(@rang:=@rang+1) as rang from ai_qhb_confirm,(select (@rang:=0)) b  order by cast(money as SIGNED INTEGER) desc ) cs where cs.unionid = '$unionid'");
		$this->assign("myRankRow",$myRankRow[0]);
		
		$this->display(C('HOME_DEFAULT_THEME').':qhbRecord');
	}		
	
	private function accessToken() {
		$tokenFile = "qhb.txt";//缓存文件名
		$data = json_decode(file_get_contents($tokenFile));
		$this->assign("times",$data->times);	
		if ($data->expire_time <= time() && $data->access_token == 0) {
			return 0;
		}elseif($data->expire_time <= time() && $data->access_token == 2) {
			return 2;
		}elseif($data->expire_time > time()) {
			return 3;
		}elseif($data->expire_time <= time() && $data->access_token == 1){
			return 1;
		}else{
			return 3;
		}
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
	
}

?>
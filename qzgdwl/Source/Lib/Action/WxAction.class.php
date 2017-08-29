<?php
class WxAction extends Action {
    protected function _initialize() {
		//session(array(''=>));
	}
	
    
	
    function tcIndex(){	
		//$url = "https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1ae00a2623048bf1&redirect_uri=http%3a%2f%2fwww.968816.com.cn%2fqzgdwl1010%2findex.php%3fm%3dqzgdwl%26a%3dqzgdwlIndex%26unionid%3d123&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
		//$userinfo_json = $this->https_request($url);	
		//$userinfo_array = json_decode($userinfo_json, true);
		//echo $_SESSION['unionid'];
		
		require_once "jssdk.php";
		$jssdk = new JSSDK("wx1ae00a2623048bf1", "adb07a65fdaf8b6d572713aa2d764d71");
		$signPackage = $jssdk->GetSignPackage();
		$this->assign("signPackage",$signPackage);
	}
		
	public function https_request($url){
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
	
}

?>
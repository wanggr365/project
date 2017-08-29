<?php
class WXChartsAction extends Action {
    protected function _initialize() {		
		//$this->isWx();
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') == false && strpos($useragent, 'Windows Phone') == false ){
			//header("location:http://www.968816.com.cn/error.html");
		}	
	}
	
	
		
    function WXChartsIndex(){	
	
		require_once "jssdk.php";
		$jssdk = new JSSDK("wx1ae00a2623048bf1", "adb07a65fdaf8b6d572713aa2d764d71");
		$signPackage = $jssdk->GetSignPackage();
		$this->assign("signPackage",$signPackage);		
		$this->display(C('HOME_DEFAULT_THEME').':WXChartsIndex');
	}	
	
	
	
}

?>
<?php
class IsBindAction extends Action {
    protected function _initialize() {		
				
	}
	
    public function index(){
		$unionid = $_GET['unionid'];
		$user = M('User');
		$uerRow = $user->where("unionid='$unionid'")->select();
		echo $uerRow[0]['is_binding'] == 1?1:0;
    }    
   
}

?>
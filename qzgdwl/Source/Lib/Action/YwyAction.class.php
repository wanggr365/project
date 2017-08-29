<?php
class YwyAction extends Action {
	
    protected function _initialize() {
	/*echo "<br>"."网站的根目录地址".__ROOT__." ";  
echo "<br>"."入口文件地址".__APP__." "; 
echo "<br>"."当前模块地址".__URL__." "; 
echo "<br>"."当前url地址".__SELF__." ";
echo "<br>"."当前操作地址".__ACTION__." ";
echo "<br>"."当前模块的模板目录".__CURRENT__." ";
echo "<br>"."当前操作名称".ACTION_NAME." ";
echo "<br>"."当前项目目录".APP_PATH." ";
echo "<br>"."当前项目名称".APP_NAME." ";
echo "<br>"."当前项目的模板目录".APP_TMPL_PATH." ";
echo "<br>"."项目的公共文件目录".APP_PUBLIC_PATH." ";
echo "<br>"."项目的配置文件目录".CONFIG_PATH." ";
echo "<br>"."项目的公共文件目录".COMMON_PATH." ";
//自动缓存与表相关的全部信息
echo "<br>"."项目的数据文件目录".DATA_PATH." runtime下的data目录";
echo "<br>"." ".GROUP_NAME."";
echo "<br>"." ".IS_CGI."";
echo "<br>"." ".IS_WIN."";
echo "<br>"." ".LANG_SET."";
echo "<br>"." ".LOG_PATH."";
echo "<br>"." ".LANG_PATH."";
echo "<br>"." ".TMPL_PATH."";
//js放入的位置，供多个应用的公共资源
echo "<br>"." ".WEB_PUBLIC_PATH."";*/
		$model = M("Setting");
		$condition['item'] = "0";
		$setting = $model->where($condition)->getField('item_key,item_value');
		$this->assign(C('AIMEE_PREFIX'),$setting);
		
		$this->assign("DiyField",$this->GetDiyField(26));
		$useragent = addslashes($_SERVER['HTTP_USER_AGENT']);
		if(strpos($useragent, 'MicroMessenger') == false && strpos($useragent, 'Windows Phone') == false ){
			header("location:http://www.968816.com.cn/error.html");
		}
	}
	
    public function index(){
		Log::write('文件名testsete：' . $tplName);
    	$this->display(C('HOME_DEFAULT_THEME').':index');
    }
    
    public function actionClass () {
		$channel = M("Channel");
		$condition['id'] = $_GET['fid'];
		$page = isset($_GET['p'])? $_GET['p'] : '1';  
		$this->assign("page",$page);
		if ($_GET['actype'] == "channel") {
			$this->assign("fid",$_GET['fid']);
			$tplName = $channel->where($condition)->getField('cl_tplclass');
		} else {
			$this->assign("cid",$_GET['cid']);
			$tplName = $channel->where($condition)->getField('cl_tplcontent');
		}
    	$this->display(C('HOME_DEFAULT_THEME').':' . $tplName);
    }
    
    public function actionPage () {
		$class = M("Page");
		$condition['id'] = $_GET['fid'];
		$page = isset($_GET['p'])? $_GET['p'] : '1';  

		$this->assign("fid",$_GET['fid']);
		$tplName = $class->where($condition)->getField('pg_tplpage');
    	$this->display(C('HOME_DEFAULT_THEME').':' . $tplName);
    }
	
    public function taglist () {
		$this->assign("keyword",mb_convert_encoding($_GET['key'],'UTF-8','auto'));
		Log::write('文件名actionClass：' . $_GET['key'] . '2222' . mb_convert_encoding($_GET['key'],'UTF-8','auto'));
		$page   = isset($_GET['p'])? $_GET['p'] : '1';  
		$this->assign("page",$page);
    	$this->display(C('HOME_DEFAULT_THEME').':taglist');
    }

	
	public function GetDiyField($type='',$row='')
	{
		$exFlag = ",";
		$model = M("Class_par");
		$condition['cl_par_attid'] = $type;
		$result = $model->where($condition)->select();
		$tempStr = "";
		foreach($result as $key => $r)
		{
			if (0 != $key) $tempStr .= '<br />';
			
			if(isset($row[$r['cl_par_name']]))
				$fieldvalue = $row[$r['cl_par_name']];
			else
				$fieldvalue = '';
	
	
			$tempStr .= '<tr';
			if($r['cl_par_type'] == 'mediumtext')
			{
				$tempStr .= ' height="304"';
			}
			$tempStr .= '><td height="35" align="right">'.$r['cl_par_key'].'：</td><td>';
	
	
			if($r['cl_par_type']=='varchar' or $r['cl_par_type']=='int' or $r['cl_par_type']=='decimal')
			{
				$tempStr .= '<input type="text" name="'.$r['cl_par_name'].'" id="'.$r['cl_par_name'].'" class="class_input" value="'.$fieldvalue.'" />';
			}
	
	
			else if($r['cl_par_type'] == 'text')
			{
				$tempStr .= '<textarea name="'.$r['cl_par_name'].'" id="'.$r['cl_par_name'].'" class="class_areatext" style="margin:7px 0;">'.$fieldvalue.'</textarea>';
			}
	
	
			else if($r['cl_par_type'] == 'radio')
			{
				if(!empty($r['cl_par_cnf']))
				{
					$cl_par_cnf = explode($exFlag, $r['cl_par_cnf']);
					foreach($cl_par_cnf as $k=>$cl_par_cnf_arr)
					{
						$cl_par_cnf_val = explode('=', $cl_par_cnf_arr);
	
						if($fieldvalue != '')
						{
							if($cl_par_cnf_val[1] == $fieldvalue)
								$checked = 'checked="checked"';
							else
								$checked = '';
						}
						else
						{
							if($k == 0)
								$checked = 'checked="checked"';
							else
								$checked = '';
						}
	
						$tempStr .= '<input type="radio" name="'.$r['cl_par_name'].'" id="'.$r['cl_par_name'].'" value="'.$cl_par_cnf_val[1].'" '.$checked.' />&nbsp;'.$cl_par_cnf_val[0];
						if($k < (count($cl_par_cnf)-1)) $tempStr .= '&nbsp;&nbsp;&nbsp;';
					}
	
				}
				
			}
	
	
			else if($r['cl_par_type'] == 'checkbox')
			{
				if(!empty($r['cl_par_cnf']))
				{
					$cl_par_cnf = explode($exFlag, $r['cl_par_cnf']);
					foreach($cl_par_cnf as $k=>$cl_par_cnf_arr)
					{
						$cl_par_cnf_val = explode('=', $cl_par_cnf_arr);
	
						if($fieldvalue != '')
						{
							$fileall = explode($exFlag,$fieldvalue);
							if(is_array($fileall))
							{
								if(in_array($cl_par_cnf_val[1], $fileall))
									$checked = 'checked="checked"';
								else
									$checked = '';
							}
							else
							{
								if($cl_par_cnf_val[1] == $fieldvalue)
									$checked = 'checked="checked"';
								else
									$checked = '';
							}
						}
						else
						{
							$checked = '';
						}
	
						$tempStr .= '<input type="checkbox" name="'.$r['cl_par_name'].'[]" id="'.$r['cl_par_name'].'[]" value="'.$cl_par_cnf_val[1].'" '.$checked.' />&nbsp;'.$cl_par_cnf_val[0];
						if($k < (count($cl_par_cnf)-1)) $tempStr .= '&nbsp;&nbsp;&nbsp;';
					}
				}
	
			}
	
	
			else if($r['cl_par_type'] == 'select')
			{
				if(!empty($r['cl_par_cnf']))
				{
	
					$tempStr .= '<select name="'.$r['cl_par_name'].'" id="'.$r['cl_par_name'].'">';
					$cl_par_cnf = explode($exFlag, $r['cl_par_cnf']);
					foreach($cl_par_cnf as $k=>$cl_par_cnf_arr)
					{
						$cl_par_cnf_val = explode('=', $cl_par_cnf_arr);
	
						if($fieldvalue != '')
						{
							if($cl_par_cnf_val[1] == $fieldvalue)
								$selected = 'selected="selected"';
							else
								$selected = '';
						}
						else
						{
							$selected = '';
						}
	
						$cl_par_cnf_val = explode('=', $cl_par_cnf_arr);
						$tempStr .= '<option name="'.$r['cl_par_name'].'" id="'.$r['cl_par_name'].'" value="'.$cl_par_cnf_val[1].'"'.$selected.' />'.$cl_par_cnf_val[0].'</option>';
						if($k < (count($cl_par_cnf)-1)) $tempStr .= '&nbsp;&nbsp;&nbsp;';
					}
					$tempStr .= '</select>';
				}
			}
	
	
			else if($r['cl_par_type'] == 'file')
			{
				$tempStr .= '<input type="text" name="'.$r['cl_par_name'].'" id="'.$r['cl_par_name'].'" class="class_input" value="'.$fieldvalue.'" />';
				$tempStr .= '<span class="cnote"><span class="gray_btn" onclick="GetUploadify(\'uploadify\',\''.$r['cl_par_key'].'\',\'all\',\'all\',1,'.$cfg_max_file_size.',\''.$r['cl_par_name'].'\')">上 传</span></span>';
			}
	
	
			else if($r['cl_par_type'] == 'mediumtext')
			{
				$tempStr .= '<textarea name="'.$r['cl_par_name'].'" id="'.$r['cl_par_name'].'" class="kindeditor">'.$fieldvalue.'</textarea>';
				$tempStr .= '<script type="text/javascript">var editor_'.$r['cl_par_name'].';KindEditor.ready(function(K) {editor_'.$r['cl_par_name'].' = K.create(\'textarea[name="'.$r['cl_par_name'].'"]\', {allowFileManager : true,width:\'667px\',height:\'280px\'});});</script>';
			}
		}
	
		$tempStr .= '</td></tr>';
		return $tempStr;
	}

    

    public function message(){

        if ($_POST['submit']) {
        	$message=M('Message');

        	$data=$_POST;

        	$data['clientip']=get_client_ip();
        	$data['postdate']=time();
 
        	if($message->data($data)->add()){
        		 
        		$this->success('提交留言成功!');
        	}else{
        		$this->error('提交留言失败');
        	}

        } else {
        
           $message=M('Message')->order("id desc")->select();
	       $this->assign("message",$message);

	       $this->display(C('HOME_DEFAULT_THEME').':message');
        }
        
    	
    }
	
	public function login(){
		/*$channel = M("Channel");
		$condition['id'] = $_GET['fid'];
		$page = isset($_GET['p'])? $_GET['p'] : '1';  
		$this->assign("page",$page);
		if ($_GET['actype'] == "channel") {
			$this->assign("fid",$_GET['fid']);
			$tplName = $channel->where($condition)->getField('cl_tplclass');
		} else {
			$this->assign("cid",$_GET['cid']);
			$tplName = $channel->where($condition)->getField('cl_tplcontent');
		}*/		
		$unionid = $_GET['unionid'];
		session('unionid',$unionid);
		$this->display(C('HOME_DEFAULT_THEME').':login');
    }
	
	public function bangding(){
		
		$this->display(C('HOME_DEFAULT_THEME').':bangding');
    }
	
	public function ywyInsertCardno(){
		
		$this->display(C('HOME_DEFAULT_THEME').':ywyInsertCardno');
    }
	
	public function ywyInsertMac(){
		
		$this->display(C('HOME_DEFAULT_THEME').':ywyInsertMac');
    }
	
	public function ywyInsertStbno(){
		
		$this->display(C('HOME_DEFAULT_THEME').':ywyInsertStbno');
    }
	
	public function ywyInsertPhone(){
		
		$this->display(C('HOME_DEFAULT_THEME').':ywyInsertPhone');
    }
	
	public function ywyInsertCertno(){
		
		$this->display(C('HOME_DEFAULT_THEME').':ywyInsertCertno');
    }
	
	public function ywyInsertAddress(){
		
		$this->display(C('HOME_DEFAULT_THEME').':ywyInsertAddress');
    }
		
	public function ywyAcct(){
		$this->is_session();
		//echo $_SESSION['own_org_id'];
		
		$this->display(C('HOME_DEFAULT_THEME').':ywyAcct');
    }
	
	public function ywyUser(){
		$this->is_session();
		$this->display(C('HOME_DEFAULT_THEME').':ywyUser');
    }
	
	public function ywwOrdering(){
		$this->is_session();
		$this->display(C('HOME_DEFAULT_THEME').':ywwOrdering');
    }
	
	public function ywyBusiness(){
		$this->is_session();
		$this->display(C('HOME_DEFAULT_THEME').':ywyBusiness');
    }
	
	public function ywyBill(){
		$this->is_session();
		$this->display(C('HOME_DEFAULT_THEME').':ywyBill');
    }
	
	public function ywyCharge(){
		$this->is_session();
		$this->display(C('HOME_DEFAULT_THEME').':ywyCharge');
    }
	
	public function ywyIndex(){
		$this->is_session();
		$this->display(C('HOME_DEFAULT_THEME').':ywyIndex');
    }
	
	public function is_session(){
		if(!$_SESSION['own_org_id']){
			$url = " https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1ae00a2623048bf1&redirect_uri=http://www.968816.com.cn/oauth2_openid_ywy.php&response_type=code&scope=snsapi_base&state=1#wechat_redirect";
			echo "<script>";  
			echo "window.location.href=\"".$url."\"";  
			echo "</script>"; 
		}
	}
	
	public function ywyCustCodeSearch(){
		
		$this->display(C('HOME_DEFAULT_THEME').':ywyCustCodeSearch');
    }
	
	public function ywyInsertName(){
		
		$this->display(C('HOME_DEFAULT_THEME').':ywyInsertName');
    }
	
	public function ywySaveLog($logStr){
		$log = M('Log');
		$data = array();
		$data['unionid'] = $_SESSION['unionid'];
		$data['create_date'] = date('Y-m-d H:i:s',time());
		$data['log'] = $logStr;
		$log->data($data)->add();
		
	}
	
	public function ywyGetCust(){
		$cust_no = "";
		$resultStr = "";
		$result = array();
		$result['CODE'] = 99;
		if(isset($_POST['custCode']) && $_SESSION['unionid']){
			$cust_no = $_POST['custCode'];
			$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=loginBossByName&custcode=$cust_no";
			$this->ywySaveLog("客户信息查询：".$_POST['custCode']);
			$values = $this->getBoss($TOKEN_URL);
			/*$unionidArr = array();
			//$unionidArr['unionid'] =$_SESSION['unionid'];
			//$ywy = M('Ywy');
			$ywyRow = $ywy->where($unionidArr)->select();
			if($ywyRow]){
				if($ywyRow[0][is_confirm] != "1"){
					$ywy->where($unionidArr)->save(array('is_confirm'=>1,'unionid'=>$_SESSION['unionid'],'openid'=>$_SESSION['openid']));
					$result['CODE'] = 1;		
				}else{
					$result['CODE'] = 2;
				}
				echo $this->json($result);
			}*/
			if($values[0]['attributes']['RETURN-CODE'] == 0 ) {	
				
				if($_SESSION['own_org_id'] == $values[1]['attributes']['OWNORGID'] || $_SESSION['own_org_id'] == "1201"){
					if($_SESSION['own_org_id'] == "2201" && (strpos($values[1]['attributes']['ADDRESS'],"鲤城")== 0 || strpos($values[1]['attributes']['ADDRESS'],"丰泽") == 0 || strpos($values[1]['attributes']['ADDRESS'],"洛江") == 0 || strpos($values[1]['attributes']['ADDRESS'],"台商") == 0 )){
						$result['CUSTOMERCODE']  = $values[1]['attributes']['CUSTOMERCODE'];
						$result['CUSTOMERNAME']  = $values[1]['attributes']['CUSTOMERNAME'];
						$result['CUSTPROP']  = $values[1]['attributes']['CUSTPROP'];
						$result['ADDRESS']  = $values[1]['attributes']['ADDRESS'];
						$result['ACCOUNTID'] = $values[1]['attributes']['ACCOUNTID'];
						$result['OWNORGID'] = $values[1]['attributes']['OWNORGID'];
						$result['OWNORGID2'] = $_SESSION['own_org_id'];
						$result['PHONE']  = $values[1]['attributes']['PHONE1']."  ".$values[1]['attributes']['PHONE2']."  ".$values[1]['attributes']['MOBILE1']."  ".$values[1]['attributes']['MOBILE2'];				
						$result['CODE'] = 0;						
						echo $this->json($result);
					}elseif($_SESSION['own_org_id'] != "2201"){
						$result['CUSTOMERCODE']  = $values[1]['attributes']['CUSTOMERCODE'];
						$result['CUSTOMERNAME']  = $values[1]['attributes']['CUSTOMERNAME'];
						$result['CUSTPROP']  = $values[1]['attributes']['CUSTPROP'];
						$result['ADDRESS']  = $values[1]['attributes']['ADDRESS'];
						$result['ACCOUNTID'] = $values[1]['attributes']['ACCOUNTID'];
						$result['OWNORGID'] = $values[1]['attributes']['OWNORGID'];
						$result['OWNORGID2'] = $_SESSION['own_org_id'];
						$result['PHONE']  = $values[1]['attributes']['PHONE1']."  ".$values[1]['attributes']['PHONE2']."  ".$values[1]['attributes']['MOBILE1']."  ".$values[1]['attributes']['MOBILE2'];				
						$result['CODE'] = 0;						
						echo $this->json($result);
					}else{
						$result['CODE'] = 43;	
						//$result['MSG'] = strpos($values[1]['attributes']['ADDRESS'],"丰泽") ;
						echo $this->json($result);
					}
				}else{
					$result['CODE'] = 43;					
					echo $this->json($result);
				}
			}else{
				echo $this->json($result);
			}
			
		}else if(!$_SESSION['unionid']){
			$result['CODE'] = 44;
			echo $this->json($result);
		}else{
			echo $this->json($result);
		}		
		
	}
	
	public function getAcctNo(){
		$cust_no = "";
		$resultStr = "";
		$result = array();
		$result['CODE'] = 99;
		if(isset($_POST['custCode'])){
			$cust_no = $_POST['custCode'];
			$TOKEN_URL_ALL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getAllInfro&custcode=$cust_no";
			$accoutNoArr = $this->getBoss($TOKEN_URL_ALL);
			$result['ACCOUNTID'] = $accoutNoArr[count($accoutNoArr)-3]['attributes']['ACCOUNTID'];
			$result['CODE'] = 0;
			echo $this->json($result);
		}else{
			echo $this->json($result);
		}
	}
	
	public function getAcctBalance(){
		$result = array();
		$result['CODE'] = 99;
		if(isset($_POST['acctNo'])){
			$acctNo = $_POST['acctNo'];
			$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getAcct&accountNo=$acctNo";			
			$this->ywySaveLog("账户信息查询：".$_POST['acctNo']);
			$values = $this->getBoss($TOKEN_URL);
			$result['NAME']  = $values[1]['attributes']['NAME'];
			$result['ACCOUNTNO']  = $values[1]['attributes']['ACCOUNTNO'];
			$result['BALANCE']  = $values[1]['attributes']['BALANCE']."元";	
			$result['PAYTYPE']  = $values[1]['attributes']['PAYTYPE'];
			$result['JOINDATE']  = $values[1]['attributes']['JOINDATE'];
			$result['OFFICE']  = $values[1]['attributes']['OFFICE'];	
			$result['STATUS']  = $values[1]['attributes']['STATUS'];		
			$result['CODE'] = 0;
			echo $this->json($result);
		}else{
			echo $this->json($result);
		}
	}
	
	public function queryBillYwy(){
		$accountNo = $_POST['accountNo'];
		$result = array();
		//echo $phone!=null ;
		$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getBill&accountNo=$accountNo";
		$this->ywySaveLog("客户账单查询：".$_POST['accountNo']);
		$values = $this->getBoss($TOKEN_URL);
		$TOKEN_URL1 ="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getAcct&accountNo=$accountNo";
		$values1 = $this->getBoss($TOKEN_URL1);
		if($values[0]['attributes']['RETURN-CODE'] == 0) {			
			$i = 0;
			$j = 0;
			$isSubBill = 0;
			foreach($values as $value1){
				if($value1['tag'] == 'BILLINFO' && isset($value1['attributes']) ){//&& $value1['attributes']['BILLCYCLE'] != date("Ym")
					$result["$i"]['BILLCYCLE'] = $value1['attributes']['BILLCYCLE'];
					$result["$i"]['AMOUNT'] = (floatval($value1['attributes']['ORIGINALAMOUNT']) + floatval($value1['attributes']['DISCOUNTAMOUNT']) + floatval($value1['attributes']['ADJUSTAMOUN']))/100;
					$result["$i"]['PPYAMOUNT'] = (floatval($value1['attributes']['PPYAMOUNT']))/100;
					$result["$i"]['UNPAYMENTCHARGE'] = $result["$i"]['AMOUNT'] - $result["$i"]['PPYAMOUNT'];
					$result["$i"]['BALANCE'] = $values1[1]['attributes']['BALANCE'];
					//
				}elseif($value1['tag'] == 'SUBBILL' && $value1['type'] == 'open'){
					$isSubBill = 1;
				}elseif($value1['tag'] == 'SUBBILL' && $value1['type'] == 'close'){
					$isSubBill = 0;
				}elseif($value1['tag'] == 'BILLDETAILINFO' && $value1['attributes']['BILLCYCLE'] == $result["$i"]['BILLCYCLE'] && $isSubBill == 1){
					$result["$i"]["$j"]['BILLCYCLE'] = $value1['attributes']['BILLCYCLE'];
					$result["$i"]["$j"]['ACCTITEMTYPENAME'] = $value1['attributes']['ACCTITEMTYPENAME'];
					$result["$i"]["$j"]['AMOUNT'] = (floatval($value1['attributes']['ORIGINALAMOUNT']) + floatval($value1['attributes']['DISCOUNTAMOUNT']) + floatval($value1['attributes']['ADJUSTAMOUN']))/100;
					$result["$i"]["$j"]['PPYAMOUNT'] = (floatval($value1['attributes']['PPYAMOUNT']))/100;
					$result["$i"]["$j"]['UNPAYMENTCHARGE'] =  $result["$i"]["$j"]['AMOUNT'] - $result["$i"]["$j"]['PPYAMOUNT'];
					$j++;
				}elseif($value1['tag'] == 'BILLINFO' && !isset($value1['attributes']) ){
					$i++;
					$j = 0;
				}
			}
			//$result['msy'] = 123;
			//echo $this->json($result);
			echo $this->json($result);
		}else{
			echo $this->json($result);
		}
	}
	
	public function queryCharge(){
		$accountNo = $_POST['accountNo'];
		$result = array();
		//echo $phone!=null ;
		$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getCharge&accountNo=$accountNo";
		$this->ywySaveLog("缴费记录查询：".$_POST['accountNo']);
		$values = $this->getBoss($TOKEN_URL);
		$TOKEN_URL1 ="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getAcct&accountNo=$accountNo";
		$values1 = $this->getBoss($TOKEN_URL1);
		if($values[0]['attributes']['RETURN-CODE'] == 0) {			
			$i = 0;
			//OFFICE PAYTIME AMOUNT PAYMENTTYPE APPPAYSTATUS APPPAYTYPE
			foreach($values as $value1){
				if($value1['tag'] == 'PAYMENTINFO') {
					$result["$i"]['OFFICE'] = $value1['attributes']['OFFICE'];
					$result["$i"]['PAYTIME'] = $value1['attributes']['PAYTIME'];
					$result["$i"]['AMOUNT'] = $value1['attributes']['AMOUNT'];
					$result["$i"]['PAYMENTTYPE'] = $value1['attributes']['PAYMENTTYPE'];
					$result["$i"]['APPPAYSTATUS'] = $value1['attributes']['APPPAYSTATUS'];
					$result["$i"]['APPPAYTYPE'] = $value1['attributes']['APPPAYTYPE'];
					$result["$i"]['BALANCE'] = $values1[1]['attributes']['BALANCE'];					
					$i++;
				}
			}				
			echo $this->json($result);
		}else{
			echo $this->json($result);
		}
	}
	
	public function queryBusiness(){
		$custCode = $_POST['custCode'];
		$startDate = $_POST['startDate'];
		$endDate = $_POST['endDate'];
		$result = array();
		//echo $phone!=null ;
		$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getBusiness&custcode=$custCode&beginDate=$startDate&endDate=$endDate";
		$this->ywySaveLog("业务办理查询：".$_POST['custCode']."  ".$startDate."  ".$endDate);
		$values = $this->getBoss($TOKEN_URL);
		if($values[0]['attributes']['RETURN-CODE'] == 0) {			
			$i = 0;
			//OFFICE PAYTIME AMOUNT PAYMENTTYPE APPPAYSTATUS APPPAYTYPE
			//SODATE BUSITYPE SOSTATUS OUTFLAG OPERNAME ORGNAME
			foreach($values as $value1){
				if($value1['tag'] == 'ORDERINFO') {
					$result["$i"]['SODATE'] = $value1['attributes']['SODATE'];
					$result["$i"]['BUSITYPE'] = $value1['attributes']['BUSITYPE'];
					$result["$i"]['SOSTATUS'] = $value1['attributes']['SOSTATUS'];
					$result["$i"]['OUTFLAG'] = $value1['attributes']['OUTFLAG'];
					$result["$i"]['OPERNAME'] = $value1['attributes']['OPERNAME'];
					$result["$i"]['ORGNAME'] = $value1['attributes']['ORGNAME'];				
					$i++;
				}
			}				
			echo $this->json($result);
		}else{
			echo $this->json($result);
		}
	}
	
	
	public function queryUser(){
		$custcode = $_POST['custcode'];
		$result = array();
		//echo $phone!=null ;
		$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getUser&custcode=$custcode";
		$this->ywySaveLog("用户信息查询：".$_POST['custcode']);
		$values = $this->getBoss($TOKEN_URL);
		if($values[0]['attributes']['RETURN-CODE'] == 0) {			
			$i = 0;
			$j = 0;
			foreach($values as $value1){
				if(isset($value1['attributes']['SUBSCRIBERID']) && $value1['attributes']['PRODSPECID'] == '800200000001' ){	   
					$result["$i"]['SUBSCRIBERID'] = $value1['attributes']['SUBSCRIBERID'];			
					$result["$i"]['SUBRELATIONTYPETITLE'] = $value1['attributes']['SUBRELATIONTYPETITLE'];
					$result["$i"]['SUBSCRIBERSTATUSSTR'] = $value1['attributes']['SUBSCRIBERSTATUSSTR'];
					$result["$i"]['STBNO'] = $value1['attributes']['STBNO'];
					$result["$i"]['CARDNO'] = $value1['attributes']['CARDNO'];		
					$result["$i"]['RESCODE'] = "";	
					//$i++;
				}elseif(isset($value1['attributes']['SUBSCRIBERID']) && $value1['attributes']['PRODSPECID'] == '800200000003'){
					$result["$i"]['SUBSCRIBERID'] = $value1['attributes']['SUBSCRIBERID'];			
					$result["$i"]['SUBRELATIONTYPETITLE'] = "宽带";
					$result["$i"]['SUBSCRIBERSTATUSSTR'] = $value1['attributes']['SUBSCRIBERSTATUSSTR'];
					$result["$i"]['STBNO'] = "";
					$result["$i"]['CARDNO'] = "";		
					$result["$i"]['RESCODE'] = "";	
					//$i++;
				}elseif(isset($value1['attributes']['RESTYPE']) && $value1['attributes']['RESTYPE'] == '机顶盒'){	
					if((isset($values[$j+1]['attributes']['RESTYPE']) && $values[$j+1]['attributes']['RESTYPE'] != "智能卡") || (isset($values[$j+2]['attributes']['RESTYPE']) && $values[$j+2]['attributes']['RESTYPE'] != "智能卡"))
						$result["$i"]['RESCODE'] = $value1['attributes']['RESCODE'];
					else{
						$result["$i"]['RESCODE'] = $value1['attributes']['RESCODE'];
						$i++;
					}
				}elseif(isset($values[$j-1]['attributes']['SUBSCRIBERID']) && isset($value1['attributes']['RESTYPE']) && ($value1['attributes']['RESTYPE'] == 'Cable Modem' || $value1['attributes']['RESTYPE'] == 'EOC')){			
					$result["$i"]['RESCODE'] = $value1['attributes']['RESCODE'];
					$result["$i"]['STBNO'] = $value1['attributes']['RESEQUNO'];
					$i++;
				}elseif(!isset($values[$j-1]['attributes']['SUBSCRIBERID']) && isset($value1['attributes']['RESTYPE']) && ($value1['attributes']['RESTYPE'] == 'Cable Modem' || $value1['attributes']['RESTYPE'] == 'EOC')){			
					if(isset($values[$j-1]['attributes']['RESTYPE'])){
						$result["$i"]['STBNO'] = $result["$i"]['STBNO'].' / '.$value1['attributes']['RESEQUNO'];	
						$result["$i"]['RESCODE'] = $result["$i"]['RESCODE'].' / '.$value1['attributes']['RESCODE'];
					}else{
						$result["$i"]['STBNO'] = $value1['attributes']['RESEQUNO'];			
						$result["$i"]['RESCODE'] = $value1['attributes']['RESCODE'];
					}
					$i++;
				}
				$j++;
			}
			//$result['msy'] = 123;
			//echo $this->json($result);
			echo $this->json($result);
		}else{
			echo $this->json($result);
		}
	}
	
	public function ywyLogin(){		
		$unionid = $_GET['unionid'];
		$openid = $_GET['openid'];
		if($unionid){
			$_SESSION['unionid'] = $unionid;
		}
		if($openid){
			$_SESSION['openid'] = $openid;
		}
		$ywy = M('Ywy');
		$unionidArr = array();
		$unionidArr['unionid'] = $unionid;
		$ywyRow = $ywy->where($unionidArr)->select();
		//echo $unionid;
		if($ywyRow && $unionid){
			if($ywyRow[0]['is_confirm'] == 1){
				$_SESSION['own_org_id'] = $ywyRow[0]['own_org_id'];				
				$this->ywySaveLog("登录");
				$this->display(C('HOME_DEFAULT_THEME').':ywyIndex');
			}else{
				$this->display(C('HOME_DEFAULT_THEME').':ywyLogin');
			}
			//echo 123;
		}else{
			$this->display(C('HOME_DEFAULT_THEME').':ywyLogin');
		}
	}
	
	public function ywyUnBunding(){
		$result = array();
		$unionidArr = array();
		$unionidArr['unionid'] = $_SESSION['unionid'];
		$ywy = M('Ywy');
		$ywyRow = $ywy->where($unionidArr)->select();
		if($ywyRow){
			$ywy->where($unionidArr)->save(array('is_confirm'=>0));			
			$this->ywySaveLog("解绑成功");
			$result['CODE'] = 1;
		}else{
			$result['CODE'] = 44;
		}
		echo $this->json($result);
	}
	
	public function ywyLoginConfirm(){	
		$result = array();
		//$boss_no = $_POST['boss_no'];
		$boss_name = $_POST['boss_name'];
		$phone = $_POST['phone'];
		$unionidArr = array();
		//$unionidArr['boss_no'] = $boss_no;
		$unionidArr['boss_name'] = $boss_name;
		$unionidArr['phone'] = $phone;
		$ywy = M('Ywy');
		$ywyRow = $ywy->where($unionidArr)->select();
		if($ywyRow && $_SESSION['unionid']){
			if($ywyRow[0]['is_confirm'] != "1"){
				$ywy->where($unionidArr)->save(array('is_confirm'=>1,'unionid'=>$_SESSION['unionid'],'openid'=>$_SESSION['openid']));
				$result['CODE'] = 1;
				$_SESSION['own_org_id'] = $ywyRow[0]['own_org_id'];				
				$this->ywySaveLog("登录成功");
			}else{
				$result['CODE'] = 2;
			}
			echo $this->json($result);
		}elseif(!$_SESSION['unionid']){
			$result['CODE'] = 0;	
			echo $this->json($result);
		}else{
			$result['CODE'] = 99;	
			echo $this->json($result);
		}
	}
	
	public function loginSubmit(){
		$unionid = session('unionid');
		if(!$unionid){
			$unionid = $_POST['unionid'];
		}
		$cust_no = $_POST['cust_no'];
		$pwd = $_POST['pwd'];
		$cust_name = $_POST['cust_name'];
		//echo $cust_no.$pwd."<br>";
		//echo $cust_name;
		if(!$unionid){
			$this->display(C('HOME_DEFAULT_THEME').':errorMessage');
		}else{
			if($pwd != "" || $cust_name == "%"){
				$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=loginBossByPwd&custcode=$cust_no&pwd=$pwd";
				$values = $this->getBoss($TOKEN_URL);
				if($values[0]['attributes']['RETURN-CODE'] == 0) {		
					//echo "登录成功";
					$user = M('User');				
					$data = array();			
					$unionidArr = array();
					$unionidArr['unionid'] = $unionid;
					$userRow = $user->where($unionidArr)->select();
					//if($userRow){
					$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=loginBossByName&custcode=$cust_no";
					$values = $this->getBoss($TOKEN_URL);
					$data['cust_code'] = $values[1]['attributes']['CUSTOMERCODE'];
					$data['cust_name'] = $values[1]['attributes']['CUSTOMERNAME'];
					$data['cust_prop'] = $values[1]['attributes']['CUSTPROP'];
					$data['address'] = $values[1]['attributes']['ADDRESS'];
					$data['phone1'] = $values[1]['attributes']['PHONE1'];
					$data['phone2'] = $values[1]['attributes']['PHONE2'];
					$data['mobile1'] = $values[1]['attributes']['MOBILE1'];
					$data['mobile2'] = $values[1]['attributes']['MOBILE2'];
					$TOKEN_URL_ALL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getAllInfro&custcode=$cust_no";
					$accoutNoArr = $this->getBoss($TOKEN_URL_ALL);
					$accoutNo = $accoutNoArr[count($accoutNoArr)-3]['attributes']['ACCOUNTID'];						
					$data['accountno'] = $accoutNo;				
					$data['is_binding'] = 1;
					$result = $user->where($unionidArr)->save($data);
					$this->display(C('HOME_DEFAULT_THEME').':bindSuc');
				}else{
					echo "客户编号或服务密码错误！";
				}
			}else{
				$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=loginBossByName&custcode=$cust_no";
				$values = $this->getBoss($TOKEN_URL);
				if($values[0]['attributes']['RETURN-CODE'] == 0) {					
					if($values[1]['attributes']['CUSTOMERNAME'] == $cust_name){					
						//echo "登录成功";
						$user = M('User');				
						$data = array();			
						$unionidArr = array();
						$unionidArr['unionid'] = $unionid;
						$userRow = $user->where($unionidArr)->select();
						//if($userRow){
						$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=loginBossByName&custcode=$cust_no";
						$values = $this->getBoss($TOKEN_URL);
						$data['cust_code'] = $values[1]['attributes']['CUSTOMERCODE'];
						$data['cust_name'] = $values[1]['attributes']['CUSTOMERNAME'];
						$data['cust_prop'] = $values[1]['attributes']['CUSTPROP'];
						$data['address'] = $values[1]['attributes']['ADDRESS'];
						$data['phone1'] = $values[1]['attributes']['PHONE1'];
						$data['phone2'] = $values[1]['attributes']['PHONE2'];
						$data['mobile1'] = $values[1]['attributes']['MOBILE1'];
						$data['mobile2'] = $values[1]['attributes']['MOBILE2'];
						//$result = $user->where($unionidArr)->save($data);		
						//}
						
						$TOKEN_URL_ALL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getAllInfro&custcode=$cust_no";
						$accoutNoArr = $this->getBoss($TOKEN_URL_ALL);
						$accoutNo = $accoutNoArr[count($accoutNoArr)-3]['attributes']['ACCOUNTID'];						
						$data['accountno'] = $accoutNo;				
						$data['is_binding'] = 1;
						$result = $user->where($unionidArr)->save($data);	
						$this->display(C('HOME_DEFAULT_THEME').':bindSuc');						
					}else{
						echo "客户编号或姓名错误！";
					}
				}else{
					echo "该客户不存在！";
				}
			}
			
		}
		
		//echo $value.$_POST['cust_no'].$_POST['cust_name'].$_POST['pwd'];
    }
	
	public function ywyCustConfirm(){
		if(isset($_POST['custCode'])){
			session('custCode',$_POST['custCode']);
		}
	}
	
	public function query(){
		$cust_name = $_POST['cust_name'];
		$address = $_POST['address'];
		$mac = $_POST['mac'];
		$stbno = $_POST['stbno'];
		$cardno = $_POST['cardno'];
		$cert_no = $_POST['cert_no'];
		$phone = $_POST['phone'];
		//echo $phone!=null ;
		if(!$address){
			if($_SESSION['own_org_id'] == "2202"){
				$address = "泉港";
			}elseif($_SESSION['own_org_id'] == "2203"){
				$address = "石狮";
			}elseif($_SESSION['own_org_id'] == "2204"){
				$address = "晋江";
			}elseif($_SESSION['own_org_id'] == "2205"){
				$address = "惠安";
			}elseif($_SESSION['own_org_id'] == "2206"){
				$address = "南安";
			}elseif($_SESSION['own_org_id'] == "2207"){
				$address = "安溪";
			}elseif($_SESSION['own_org_id'] == "2208"){
				$address = "永春";
			}elseif($_SESSION['own_org_id'] == "2209"){
				$address = "德化";
			}
		}
		$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getCustCode&cardno=$cardno&address=$address&mac=$mac&stbno=$stbno&cust_name=$cust_name&cert_no=$cert_no&phone=$phone";
		$this->ywySaveLog("获取客户编号：cardno=$cardno&address=$address&mac=$mac&stbno=$stbno&cust_name=$cust_name&cert_no=$cert_no&phone=$phone");
		$values = $this->getBoss($TOKEN_URL);
		if($values[0]['attributes']['RETURN-CODE'] == 0) {
			$returnStr = "";
			$arrLen = count($values);
			for ($i = 1;$i < $arrLen-1; $i++){ 
				$returnStr .= "<div class=\"queryCust\">".$i.". 客户编号：<span id=\"custCode".$i."\" style=\"color:#CC0033\">".$values[$i]['attributes']['CUSTCODE']."</span><br>"."地址：".$values[$i]['attributes']['ADDRNAME']."<br>".
				"<div class=\"col-50\"><a href=\"#\" onclick=\"ywyCustConfirm(".$i.");\" class=\"weui_btn_sure\">确定</a></div>".
				"----------------------------------------------<br></div>";
			} 
			echo $returnStr;
		}else{
			echo "该客户不存在！";
		}
    }
	
	public function queryBill(){
		$accountNo = $_POST['accountNo'];
		$result = array();
		//echo $phone!=null ;
		$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getBill&accountNo=$accountNo";
		$values = $this->getBoss($TOKEN_URL);
		if($values[0]['attributes']['RETURN-CODE'] == 0) {			
			$i = 0;
			foreach($values as $value1){
				if($value1['tag'] == 'BILLINFO' && isset($value1['attributes']) && $value1['attributes']['BILLCYCLE'] != date("Ym")){
					$result["$i"]['BILLCYCLE'] = $value1['attributes']['BILLCYCLE'];
					$result["$i"]['AMOUNT'] = (floatval($value1['attributes']['ORIGINALAMOUNT']) + floatval($value1['attributes']['DISCOUNTAMOUNT']) + floatval($value1['attributes']['ADJUSTAMOUN']))/100;
					$result["$i"]['PPYAMOUNT'] = (floatval($value1['attributes']['PPYAMOUNT']))/100;
					$result["$i"]['UNPAYMENTCHARGE'] = $result["$i"]['AMOUNT'] - $result["$i"]['PPYAMOUNT'];
					$i++;
				}
			}
			//$result['msy'] = 123;
			//echo $this->json($result);
			echo $this->json($result);
		}else{
			echo $this->json($result);
		}
	}
	
	public function queryAllInfor(){
		$custcode = $_POST['custcode'];
		$result = array();
		//echo $phone!=null ;
		$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getAllInfro&custcode=$custcode";
		$values = $this->getBoss($TOKEN_URL);
		if($values[0]['attributes']['RETURN-CODE'] == 0) {			
			$i = 0;
			foreach($values as $value1){
				if(isset($value1['attributes']['SUBSCRIBERID']) ){
					$result["$i"]['SUBSCRIBERID'] = $value1['attributes']['SUBSCRIBERID'];
					$result["$i"]['STBNO'] = $value1['attributes']['STBNO'] == "" ? '宽带' : $value1['attributes']['STBNO'];
					$result["$i"]['SUBRELATIONTYPETITLE'] = $value1['attributes']['SUBRELATIONTYPETITLE'];
					$i++;
				}
			}
			//$result['msy'] = 123;
			//echo $this->json($result);
			echo $this->json($result);
		}else{
			echo $this->json($result);
		}
	}
	
	public function queryAllOrdering(){
		$SUBSCRIBERID = $_POST['SUBSCRIBERID'];
		$result = array();
		//echo $phone!=null ;
		$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getOrding&subscriberNo=$SUBSCRIBERID";
		$this->ywySaveLog("用户订购查询：".$_POST['SUBSCRIBERID']);
		$values = $this->getBoss($TOKEN_URL);
		if($values[0]['attributes']['RETURN-CODE'] == 0) {			
			$i = 0;
			foreach($values as $value1){
				if(isset($value1['attributes']['OFFERID']) ){
					$result["$i"]['OFFERNAME'] = $value1['attributes']['OFFERNAME'];				
				}elseif(isset($value1['attributes']['PRODUCTID']) ){
					if(!isset($result["$i"]['OFFERNAME'])){
						$l = $i - 1;
						$result["$i"]['OFFERNAME'] = $result["$l"]['OFFERNAME'];
					}
					$result["$i"]['PRODUCTNAME'] = $value1['attributes']['PRODUCTNAME'];	
					$result["$i"]['SUBSTATUSSTR'] = $value1['attributes']['SUBSTATUSSTR'];	
					$result["$i"]['JOINDATE'] = substr($value1['attributes']['JOINDATE'],0,10);	
					$result["$i"]['VALIDDAYS'] = substr($value1['attributes']['VALIDDAYS'],0,10);	
					$result["$i"]['EXPIREDAYS'] = substr($value1['attributes']['EXPIREDAYS'],0,10);					
					$i++;
				}				
			}
			if($i == 0){
				$result = array();
			}
			//$result['msy'] = 123;
			//echo $this->json($result);
			echo $this->json($result);
		}else{
			echo $this->json($result);
		}
	}
	
	public function ywyOrdering(){
		
		$this->display(C('HOME_DEFAULT_THEME').':ywyOrdering');
    }
	
	public function test(){
		define("TOKEN", "ywkfzx");
        //echo $_GET["echostr"];        
		$wechatObj = new wechatCallbackapiTest();
    	if (!isset($_GET['echostr'])) {	
			//$access_token = $wechatObj->accessToken();
			//echo $access_token;
			//echo 123;
			$wechatObj->responseMsg();
		}else{
			//echo 333;
			$wechatObj->valid();
		}
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


}

class wechatCallbackapiTest
{
    public function valid()
    {
        $echoStr = $_GET["echostr"];
        if($this->checkSignature()){
            echo $echoStr;
            exit;
        }
    }

    private function checkSignature()
    {
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];

        $token = TOKEN;
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }

    public function responseMsg()
    {
        $postStr = $GLOBALS["HTTP_RAW_POST_DATA"];
        if (!empty($postStr)){
            $postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
            $RX_TYPE = trim($postObj->MsgType);

            switch ($RX_TYPE)
            {
                case "text":
                    $resultStr = $this->receiveText($postObj);
                    break;
                case "event":
                    $resultStr = $this->receiveEvent($postObj);
                    break;
                default:
                    $resultStr = "";
                    break;
            }
            echo $resultStr;
        }else {
            echo "";
            exit;
        }
    }

    private function receiveText($object)
    {
        $funcFlag = 0;
        $contentStr = "中国国旗：".$this->utf8_bytes(0x1F60A)."\n";//"<a href = \"http://www.qq.com\">欢迎关注！</a>".$object->Content;
        $resultStr = $this->transmitText($object, $contentStr, $funcFlag);
        return $resultStr;
    }
    
    private function receiveEvent($object)
    {
        $contentStr = "";
		$openid = "";
        switch ($object->Event)
        {
            case "subscribe":
                //$contentStr = $this->accessToken();
				$access_token = $this->accessToken();
				$openid = $object->FromUserName;
				$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
				//$output = $this->https_request($url);
				//$jsoninfo = json_decode($output, true);
				$output = $this->https_request($url);
				$jsoninfo = json_decode($output, true);
				//$contentStr = "<a href = \" http://www.qq.com \">"."欢迎关注！"."</a>";
				/*"您好，".$jsoninfo["nickname"]."\n".
				"性别：".(($jsoninfo["sex"] == 1)?"男":(($jsoninfo["sex"] == 2)?"女":"未知"))."\n".
				"地区：".$jsoninfo["country"]." ".$jsoninfo["province"]." ".$jsoninfo["city"]."\n".
				"语言：".(($jsoninfo["language"] == "zh_CN")?"简体中文":"非简体中文")."\n".
				"openid：".$openid."\n".
				"unionid: ".$jsoninfo["unionid"]."\n".
				"关注：".date('Y年m月d日',$jsoninfo["subscribe_time"]);*/
				$user = M('User');				
				$data = array();			
				$unionidArr = array();
				$unionidArr['unionid'] = $jsoninfo["unionid"];
				$userRow = $user->where($unionidArr)->select();
				if($userRow){
					$data['nickname'] = $jsoninfo["nickname"];
					$data['sex'] = ($jsoninfo["sex"] == 1)?"男":(($jsoninfo["sex"] == 2)?"女":"未知");
					$data['country'] = $jsoninfo["country"];
					$data['province'] = $jsoninfo["province"];
					$data['city'] = $jsoninfo["city"];
					$data['subscribe_time'] = date('Y-m-d H:i:s',$jsoninfo["subscribe_time"]);
					$data['is_subscribe'] = 1;
					$result = $user->where($unionidArr)->save($data);					
					$contentStr = "<a href = \" http://www.qq.com \">"."欢迎关注！"."</a>";
				}else{
					$data['unionid'] = $jsoninfo["unionid"];
					$data['openid'] = $jsoninfo["openid"];
					$data['nickname'] = $jsoninfo["nickname"];
					$data['sex'] = ($jsoninfo["sex"] == 1)?"男":(($jsoninfo["sex"] == 2)?"女":"未知");
					$data['country'] = $jsoninfo["country"];
					$data['province'] = $jsoninfo["province"];
					$data['city'] = $jsoninfo["city"];
					$data['subscribe_time'] = date('Y-m-d H:i:s',$jsoninfo["subscribe_time"]);
					$data['cust_code'] = "";
					$data['cust_name'] = "";
					$data['cust_prop'] = "";
					$data['address'] = "";
					$data['phone1'] = "";
					$data['phone2'] = "";
					$data['mobile1'] = "";
					$data['mobile2'] = "";
					$data['is_subscribe'] = 1;
					$result = $user->add($data);
				}		
				$unionid = $jsoninfo["unionid"];
				$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
				break;   
            case "unsubscribe":
				$access_token = $this->accessToken();
				$openid = $object->FromUserName;
				$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
				$output = $this->https_request($url);
				$jsoninfo = json_decode($output, true);				
				$user = M('User');				
				$data = array();			
				$unionidArr = array();
				$unionidArr['unionid'] = $jsoninfo["unionid"];
				$userRow = $user->where($unionidArr)->select();
				if($userRow){
					$data['is_subscribe'] = 0;
					$result = $user->where($unionidArr)->save($data);
				}else{
					$data['unionid'] = $jsoninfo["unionid"];
					$data['openid'] = $jsoninfo["openid"];
					
					$data['cust_code'] = "";
					$data['cust_name'] = "";
					$data['cust_prop'] = "";
					$data['address'] = "";
					$data['phone1'] = "";
					$data['phone2'] = "";
					$data['mobile1'] = "";
					$data['mobile2'] = "";
					$data['is_subscribe'] = 0;
					$result = $user->add($data);
				}
                break;
            case "CLICK":
                switch ($object->EventKey)
                {
                    case "ACCT":
                        $access_token = $this->accessToken();
						$openid = $object->FromUserName;
						$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
						$output = $this->https_request($url);
						$jsoninfo = json_decode($output, true);						
						$unionid = $jsoninfo["unionid"];
						$user = M('User');				
						$data = array();			
						$unionidArr = array();
						$unionidArr['unionid'] = $jsoninfo["unionid"];
						$userRow = $user->where($unionidArr)->select();
						if($userRow){
							if($userRow[0]['is_subscribe'] != "1" && $userRow[0]['is_binding'] != "1"){
								$data['nickname'] = $jsoninfo["nickname"];
								$data['sex'] = ($jsoninfo["sex"] == 1)?"男":(($jsoninfo["sex"] == 2)?"女":"未知");
								$data['country'] = $jsoninfo["country"];
								$data['province'] = $jsoninfo["province"];
								$data['city'] = $jsoninfo["city"];
								$data['subscribe_time'] = date('Y-m-d H:i:s',$jsoninfo["subscribe_time"]);
								$data['is_subscribe'] = 1;			

								$result = $user->where($unionidArr)->save($data);								
								$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
								break;
							}elseif($userRow[0]['is_binding'] != "1"){								
								$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
								break;
							}else{
								$accountNo = $userRow[0]['accountno'];
								$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getAcct&accountNo=$accountNo";
								$values = $this->getBoss1($TOKEN_URL);
								$contentStr = "您的账户余额：".$values[1]['attributes']['BALANCE']."元";
								break;
							}							
							
						}else{
							$data['unionid'] = $jsoninfo["unionid"];
							$data['openid'] = $jsoninfo["openid"];
							$data['nickname'] = $jsoninfo["nickname"];
							$data['sex'] = ($jsoninfo["sex"] == 1)?"男":(($jsoninfo["sex"] == 2)?"女":"未知");
							$data['country'] = $jsoninfo["country"];
							$data['province'] = $jsoninfo["province"];
							$data['city'] = $jsoninfo["city"];
							$data['subscribe_time'] = date('Y-m-d H:i:s',$jsoninfo["subscribe_time"]);
							$data['cust_code'] = "";
							$data['cust_name'] = "";
							$data['cust_prop'] = "";
							$data['address'] = "";
							$data['phone1'] = "";
							$data['phone2'] = "";
							$data['mobile1'] = "";
							$data['mobile2'] = "";
							$data['is_subscribe'] = 1;
							$result = $user->add($data);
							$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
						}		
                        break;
					case "PRODUCT":
                        $access_token = $this->accessToken();
						$openid = $object->FromUserName;
						$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
						$output = $this->https_request($url);
						$jsoninfo = json_decode($output, true);						
						$unionid = $jsoninfo["unionid"];
						$user = M('User');				
						$data = array();			
						$unionidArr = array();
						$unionidArr['unionid'] = $jsoninfo["unionid"];
						$userRow = $user->where($unionidArr)->select();
						if($userRow){
							if($userRow[0]['is_subscribe'] != "1" && $userRow[0]['is_binding'] != "1"){
								$data['nickname'] = $jsoninfo["nickname"];
								$data['sex'] = ($jsoninfo["sex"] == 1)?"男":(($jsoninfo["sex"] == 2)?"女":"未知");
								$data['country'] = $jsoninfo["country"];
								$data['province'] = $jsoninfo["province"];
								$data['city'] = $jsoninfo["city"];
								$data['subscribe_time'] = date('Y-m-d H:i:s',$jsoninfo["subscribe_time"]);
								$data['is_subscribe'] = 1;			

								$result = $user->where($unionidArr)->save($data);								
								$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
								break;
							}elseif($userRow[0]['is_binding'] != "1"){								
								$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
								break;
							}else{
								$custcode = $userRow[0]['cust_code'];
								//$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getAcct&accountNo=$accountNo";
								//$values = $this->getBoss1($TOKEN_URL);
								//$contentStr = "您的账户余额：".$values[1]['attributes']['BALANCE'];
								
								$contentStr[] = array("Title" =>"在用产品", 
								"Description" =>"查看您目前在用的广电网络套餐产品", 
								"PicUrl" =>"http://www.968816.com.cn/wxBoss/Statics/Default/Images/product.jpg", 
								"Url" =>"http://www.968816.com.cn/index.php?m=index&a=userList&custcode=$custcode");
								break;
							}							
							
						}else{
							$data['unionid'] = $jsoninfo["unionid"];
							$data['openid'] = $jsoninfo["openid"];
							$data['nickname'] = $jsoninfo["nickname"];
							$data['sex'] = ($jsoninfo["sex"] == 1)?"男":(($jsoninfo["sex"] == 2)?"女":"未知");
							$data['country'] = $jsoninfo["country"];
							$data['province'] = $jsoninfo["province"];
							$data['city'] = $jsoninfo["city"];
							$data['subscribe_time'] = date('Y-m-d H:i:s',$jsoninfo["subscribe_time"]);
							$data['cust_code'] = "";
							$data['cust_name'] = "";
							$data['cust_prop'] = "";
							$data['address'] = "";
							$data['phone1'] = "";
							$data['phone2'] = "";
							$data['mobile1'] = "";
							$data['mobile2'] = "";
							$data['is_subscribe'] = 1;
							$result = $user->add($data);
							$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
						}		
                        break;
					case "BILL":
                        $access_token = $this->accessToken();
						$openid = $object->FromUserName;
						$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
						$output = $this->https_request($url);
						$jsoninfo = json_decode($output, true);						
						$unionid = $jsoninfo["unionid"];
						$user = M('User');				
						$data = array();			
						$unionidArr = array();
						$unionidArr['unionid'] = $jsoninfo["unionid"];
						$userRow = $user->where($unionidArr)->select();
						if($userRow){
							if($userRow[0]['is_subscribe'] != "1" && $userRow[0]['is_binding'] != "1"){
								$data['nickname'] = $jsoninfo["nickname"];
								$data['sex'] = ($jsoninfo["sex"] == 1)?"男":(($jsoninfo["sex"] == 2)?"女":"未知");
								$data['country'] = $jsoninfo["country"];
								$data['province'] = $jsoninfo["province"];
								$data['city'] = $jsoninfo["city"];
								$data['subscribe_time'] = date('Y-m-d H:i:s',$jsoninfo["subscribe_time"]);
								$data['is_subscribe'] = 1;			

								$result = $user->where($unionidArr)->save($data);								
								$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
								break;
							}elseif($userRow[0]['is_binding'] != "1"){								
								$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
								break;
							}else{
								$accountNo = $userRow[0]['accountno'];
								//$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getAcct&accountNo=$accountNo";
								//$values = $this->getBoss1($TOKEN_URL);
								//$contentStr = "您的账户余额：".$values[1]['attributes']['BALANCE'];
								
								$contentStr[] = array("Title" =>"历史账单", 
								"Description" =>"查看您一年的有线电视费账单", 
								"PicUrl" =>"http://www.968816.com.cn/wxBoss/Statics/Default/Images/bill.jpg", 
								"Url" =>"http://www.968816.com.cn/index.php?m=index&a=bill&accountNo=$accountNo");
								break;
							}							
							
						}else{
							$data['unionid'] = $jsoninfo["unionid"];
							$data['openid'] = $jsoninfo["openid"];
							$data['nickname'] = $jsoninfo["nickname"];
							$data['sex'] = ($jsoninfo["sex"] == 1)?"男":(($jsoninfo["sex"] == 2)?"女":"未知");
							$data['country'] = $jsoninfo["country"];
							$data['province'] = $jsoninfo["province"];
							$data['city'] = $jsoninfo["city"];
							$data['subscribe_time'] = date('Y-m-d H:i:s',$jsoninfo["subscribe_time"]);
							$data['cust_code'] = "";
							$data['cust_name'] = "";
							$data['cust_prop'] = "";
							$data['address'] = "";
							$data['phone1'] = "";
							$data['phone2'] = "";
							$data['mobile1'] = "";
							$data['mobile2'] = "";
							$data['is_subscribe'] = 1;
							$result = $user->add($data);
							$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
						}		
                        break;
					case "CHARGE":
                        $access_token = $this->accessToken();
						$openid = $object->FromUserName;
						$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
						$output = $this->https_request($url);
						$jsoninfo = json_decode($output, true);						
						$unionid = $jsoninfo["unionid"];
						$user = M('User');				
						$data = array();			
						$unionidArr = array();
						$unionidArr['unionid'] = $jsoninfo["unionid"];
						$userRow = $user->where($unionidArr)->select();
						if($userRow){
							if($userRow[0]['is_subscribe'] != "1" && $userRow[0]['is_binding'] != "1"){
								$data['nickname'] = $jsoninfo["nickname"];
								$data['sex'] = ($jsoninfo["sex"] == 1)?"男":(($jsoninfo["sex"] == 2)?"女":"未知");
								$data['country'] = $jsoninfo["country"];
								$data['province'] = $jsoninfo["province"];
								$data['city'] = $jsoninfo["city"];
								$data['subscribe_time'] = date('Y-m-d H:i:s',$jsoninfo["subscribe_time"]);
								$data['is_subscribe'] = 1;			

								$result = $user->where($unionidArr)->save($data);								
								$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
								break;
							}elseif($userRow[0]['is_binding'] != "1"){								
								$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
								break;
							}else{
								$accountNo = $userRow[0]['accountno'];
								$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getAcct&accountNo=$accountNo";
								$values = $this->getBoss1($TOKEN_URL);
								$contentStr = "您的账户余额：".$values[1]['attributes']['BALANCE'];
								break;
							}							
							
						}else{
							$data['unionid'] = $jsoninfo["unionid"];
							$data['openid'] = $jsoninfo["openid"];
							$data['nickname'] = $jsoninfo["nickname"];
							$data['sex'] = ($jsoninfo["sex"] == 1)?"男":(($jsoninfo["sex"] == 2)?"女":"未知");
							$data['country'] = $jsoninfo["country"];
							$data['province'] = $jsoninfo["province"];
							$data['city'] = $jsoninfo["city"];
							$data['subscribe_time'] = date('Y-m-d H:i:s',$jsoninfo["subscribe_time"]);
							$data['cust_code'] = "";
							$data['cust_name'] = "";
							$data['cust_prop'] = "";
							$data['address'] = "";
							$data['phone1'] = "";
							$data['phone2'] = "";
							$data['mobile1'] = "";
							$data['mobile2'] = "";
							$data['is_subscribe'] = 1;
							$result = $user->add($data);
							$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
						}		
                        break;
					case "BINDING":
                        $access_token = $this->accessToken();
						$openid = $object->FromUserName;
						$url = "https://api.weixin.qq.com/cgi-bin/user/info?access_token=$access_token&openid=$openid&lang=zh_CN";
						$output = $this->https_request($url);
						$jsoninfo = json_decode($output, true);						
						$unionid = $jsoninfo["unionid"];
						$user = M('User');				
						$data = array();			
						$unionidArr = array();
						$unionidArr['unionid'] = $jsoninfo["unionid"];
						$userRow = $user->where($unionidArr)->select();
						if($userRow){
							if($userRow[0]['is_subscribe'] != "1" && $userRow[0]['is_binding'] != "1"){
								$data['nickname'] = $jsoninfo["nickname"];
								$data['sex'] = ($jsoninfo["sex"] == 1)?"男":(($jsoninfo["sex"] == 2)?"女":"未知");
								$data['country'] = $jsoninfo["country"];
								$data['province'] = $jsoninfo["province"];
								$data['city'] = $jsoninfo["city"];
								$data['subscribe_time'] = date('Y-m-d H:i:s',$jsoninfo["subscribe_time"]);
								$data['is_subscribe'] = 1;			

								$result = $user->where($unionidArr)->save($data);								
								$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
								break;
							}elseif($userRow[0]['is_binding'] != "1"){								
								$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
								break;
							}else{
								$accountNo = $userRow[0]['accountno'];
								$TOKEN_URL="http://36.250.88.58:8085/ppjj/tvPpjj.action?method=getAcct&accountNo=$accountNo";
								$values = $this->getBoss1($TOKEN_URL);
								$contentStr = "您已绑定客户编号：".$userRow[0]['cust_code']."，姓名：".$userRow[0]['cust_name']."，地址：".$userRow[0]['address']."。";
								break;
							}							
							
						}else{
							$data['unionid'] = $jsoninfo["unionid"];
							$data['openid'] = $jsoninfo["openid"];
							$data['nickname'] = $jsoninfo["nickname"];
							$data['sex'] = ($jsoninfo["sex"] == 1)?"男":(($jsoninfo["sex"] == 2)?"女":"未知");
							$data['country'] = $jsoninfo["country"];
							$data['province'] = $jsoninfo["province"];
							$data['city'] = $jsoninfo["city"];
							$data['subscribe_time'] = date('Y-m-d H:i:s',$jsoninfo["subscribe_time"]);
							$data['cust_code'] = "";
							$data['cust_name'] = "";
							$data['cust_prop'] = "";
							$data['address'] = "";
							$data['phone1'] = "";
							$data['phone2'] = "";
							$data['mobile1'] = "";
							$data['mobile2'] = "";
							$data['is_subscribe'] = 1;
							$result = $user->add($data);
							$contentStr = "<a href='http://www.968816.com.cn/index.php?m=index&a=login&unionid=$unionid'>点击绑定账号</a>".$this->utf8_bytes(0x1F60A);
						}		
                        break;																
                    default:
                        $contentStr[] = array("Title" =>"默认菜单回复", 
                        "Description" =>"您正在使用的是方倍工作室的自定义菜单测试接口", 
                        "PicUrl" =>"http://discuz.comli.com/weixin/weather/icon/cartoon.jpg", 
                        "Url" =>"weixin://addfriend/pondbaystudio");
                        break;
                }
                break;
            default:
                break;      

        }
        if (is_array($contentStr)){
            $resultStr = $this->transmitNews($object, $contentStr);
        }else{
            $resultStr = $this->transmitText($object, $contentStr);
        }
        return $resultStr;
    }

    private function transmitText($object, $content, $funcFlag = 0)
    {
        $textTpl = "<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%s</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
<FuncFlag>%d</FuncFlag>
</xml>";
        $resultStr = sprintf($textTpl, $object->FromUserName, $object->ToUserName, time(), $content, $funcFlag);
        return $resultStr;
    }

    private function transmitNews($object, $arr_item, $funcFlag = 0)
    {
        //首条标题28字，其他标题39字
        if(!is_array($arr_item))
            return;

        $itemTpl = "    <item>
					<Title><![CDATA[%s]]></Title>
					<Description><![CDATA[%s]]></Description>
					<PicUrl><![CDATA[%s]]></PicUrl>
					<Url><![CDATA[%s]]></Url>
				</item>
			";
        $item_str = "";
        foreach ($arr_item as $item)
            $item_str .= sprintf($itemTpl, $item['Title'], $item['Description'], $item['PicUrl'], $item['Url']);

        $newsTpl = "<xml>
			<ToUserName><![CDATA[%s]]></ToUserName>
			<FromUserName><![CDATA[%s]]></FromUserName>
			<CreateTime>%s</CreateTime>
			<MsgType><![CDATA[news]]></MsgType>
			<Content><![CDATA[]]></Content>
			<ArticleCount>%s</ArticleCount>
			<Articles>
			$item_str</Articles>
			<FuncFlag>%s</FuncFlag>
			</xml>";

        $resultStr = sprintf($newsTpl, $object->FromUserName, $object->ToUserName, time(), count($arr_item), $funcFlag);
        return $resultStr;
    }
	
	
	  private function accessToken() {
		$tokenFile = "../wxBoss/access_token.txt";//缓存文件名
		$data = json_decode(file_get_contents($tokenFile));
		//print_r($data);
		if ($data->expire_time < time() or !$data->expire_time) {
		$appid = "wx1ae00a2623048bf1";
		$appsecret = "adb07a65fdaf8b6d572713aa2d764d71";
		$url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$appsecret";
		  $res = $this->getJson($url);
		  $access_token = $res['access_token'];
		  if($access_token) {
				$data1['expire_time'] = time() + 7000;
				$data1['access_token'] = $access_token;
				$fp = fopen($tokenFile, "w");
				fwrite($fp, json_encode($data1));
				fclose($fp);
			}
		} else {
			$access_token = $data->access_token;
		}
		return $access_token;
	  }
   
//取得微信返回的JSON数据
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
	
	private function utf8_bytes($cp)
	{
		if ($cp > 0x10000){
			# 4 bytes
			return	chr(0xF0 | (($cp & 0x1C0000) >> 18)).
					chr(0x80 | (($cp & 0x3F000) >> 12)).
					chr(0x80 | (($cp & 0xFC0) >> 6)).
					chr(0x80 | ($cp & 0x3F));
		}else if ($cp > 0x800){
			# 3 bytes
			return	chr(0xE0 | (($cp & 0xF000) >> 12)).
					chr(0x80 | (($cp & 0xFC0) >> 6)).
					chr(0x80 | ($cp & 0x3F));
		}else if ($cp > 0x80){
			# 2 bytes
			return	chr(0xC0 | (($cp & 0x7C0) >> 6)).
					chr(0x80 | ($cp & 0x3F));
		}else{
			# 1 byte
			return chr($cp);
		}
	}
	
	public function getBoss1($param){
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
}

?>
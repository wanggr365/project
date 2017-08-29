function switchInput(input_type)
{
	  if(input_type=="1")
	  {
	  	  document.getElementById('password').style.display="block";
	  	  document.getElementById('linkphone').style.display="none";
		  $("input[name='cust_name']")[0].value = "%";
	  }
	  else
	  {
		  $("input[name='cust_name']")[0].value = "";
	      document.getElementById('linkphone').style.display="block";
	  	  document.getElementById('password').style.display="none";
		  $("input[name='pwd']")[0].value = "";
	  }
}

function ywyCustConfirm(_i){
	location.href = "index.php?m=qzgdwl&a=qzgdwlLogin&custCode="+document.getElementById('custCode'+_i).innerHTML;
}

function getYwyBill(){
	location.href = "index.php?m=qzgdwl&a=ywyBill&acctNo="+Request['acctNo'].toString();
}

function getYwyOrding(SUBSCRIBERID){
	location.href = "index.php?m=qzgdwl&a=ywyOrdering&SUBSCRIBERID="+SUBSCRIBERID;
}

function refreshOrder(prodInstId,card_no,opType,isCard){
	$.showIndicator();
	var opName = isCard == "1"?'猫号':'卡号';
	var opTypeName = opType=="2"?'LDAP授权':opType=="5"?'CA授权':'解配对';
	$.confirm('确定为' + opName + '<br>' + card_no + '进行<br><b>' + opTypeName + '</b>', function () {
		$.showIndicator();
		$.ajax({
			type:'post',
			  url: "index.php?m=qzgdwl&a=qzgdwlRefreshOrder",
			  context: document.body,
			  dataType:'json',  
			  data:"prodInstId="+ prodInstId + "&opType=" + opType + "&card_no=" + card_no,
			  success: function(json){
					if(json.code == "0"){
						$.alert(json.msg,'错误');
					}else{
						$.alert('业务办理查询最终结果','成功');
					}
					$.hideIndicator();
			  }
		});	
	
    });
	$.hideIndicator();	
}

function getSubBill(id,id1){
	//document.getElementById(id).style.display="block";
	//$("tr[id=^'" + id + "']").css('display','block');  
	//alert(321);
	var list=$('tr[id^=' + id + ']');
	if($('#'+id1).html() == "查看详单"){
		for(var i=0;i<list.length;i++){
			list[i].style.display = 'table-row' ;
		}
		$('#'+id1).html('隐藏详单');
	}else{
		for(var i=0;i<list.length;i++){
			list[i].style.display = 'none' ;
		}		
		$('#'+id1).html('查看详单');
	}
	//alert(123);
}
function goGetCustCode(){
	location.href = "index.php?m=qzgdwl&a=ywyCustCodeSearch";
}
function goUrl(_i){	
	if($("#custCode").html() != "" ||  _i == 7 ||  _i == 8){
		var acctNo = "";
		switch(_i){
			case 1:			
			window.location.href = "http://www.968816.com.cn/index.php?m=qzgdwl&a=ywyAcct&acctNo="+$("#acctNo").html()+"&custCode="+$("#custCode").html();		
			break;
			case 2:			
			window.location.href = "http://www.968816.com.cn/index.php?m=qzgdwl&a=ywyUser&custCode="+$("#custCode").html();	
			break;
			case 3:			
			window.location.href = "http://www.968816.com.cn/index.php?m=qzgdwl&a=ywwOrdering&custCode="+$("#custCode").html();	
			break;
			case 4:			
			window.location.href = "http://www.968816.com.cn/index.php?m=qzgdwl&a=ywyBusiness&custCode="+$("#custCode").html();	
			break;
			case 5:			
			window.location.href = "http://www.968816.com.cn/index.php?m=qzgdwl&a=ywyBill&acctNo="+$("#acctNo").html();
			break;
			case 6:			
			window.location.href = "http://www.968816.com.cn/index.php?m=qzgdwl&a=ywyCharge&custCode="+$("#custCode").html() + "&acctNo="+$("#acctNo").html();	
			break;
			case 7:			
			window.location.href = "http://www.968816.cn/cmtsapp/index.html";	
			break;
			case 8:			
			window.location.href = "http://eoc.968816.cn/eocwhat/index.jhtml";	
			break;
		}
	}else{
		$.alert('请先输入客户编号'+'</br>'+'如不清楚，点击屏幕下方查询', '错误!');
	}
}

function goBack(){
	/*var Request = new Object(); 			
	Request = GetRequest(); 
	window.location.href = "http://www.968816.com.cn/index.php?m=qzgdwl&a=ywyIndex&custCode="+Request['custCode'].toString();*/
	window.history.go(-1);
}

function queryCustInfo()
{
	  if(BASEtrim(document.getElementById("search").value)=="")
	  {
	  	  //document.getElementById('result').innerHTML="请输入客户客户编号!";
		  alert("请输入客户编号");
		  //document.getElementById("search").focus();
	  	  return false;
	  }
	  $.showIndicator();
	  $.ajax({
			type:'post',
			  url: "index.php?m=qzgdwl&a=ywyGetCust",
			  context: document.body,
			  dataType:'json',  
			  data:"custCode="+BASEtrim(document.getElementById("search").value),
			  success: function(json){
					if(json.CODE == "99"){
						$.alert('当前客户不存在！', '错误!');
						//alert("当前客户不存在！");
						$.hideIndicator();
					}else if(json.CODE == "44"){
						$.alert('输入超时，请关闭页面重新进入！', '错误!');
						//alert("当前客户不存在！");
						$.hideIndicator();
					}else if(json.CODE == "43"){
						$.alert('只能获取本区域的客户信息！', '错误!');
						//alert("当前客户不存在！");
						$.hideIndicator();
					}else{
						//$("#custCode").html(json.CUSTOMERCODE);
						//$("#custName").html(json.CUSTOMERNAME);
						//$("#custProp").html(json.CUSTPROP);
						//$("#address").html(json.ADDRESS);
						//$("#phone").html(json.PHONE);
						//$("#acctNo").html(json.ACCOUNTID);
						location.href = "index.php?m=qzgdwl&a=ywyIndex&custCode=" + json.CUSTOMERCODE;
					}
			  }
		});	
}

function unBunding()
{
	  $.confirm('确定解绑吗?解绑后需要重新登记才能使用系统！', function () {
        //$.alert('You clicked OK button!');
   
		  $.showIndicator();
		  $.ajax({
				type:'post',
				  url: "index.php?m=qzgdwl&a=ywyUnBunding",
				  context: document.body,
				  dataType:'json',  
				  data:"custCode="+BASEtrim(document.getElementById("search").value),
				  success: function(json){
						if(json.CODE == "44"){
							$.alert('超时，请关闭页面重新进入！', '错误!');
							//alert("当前客户不存在！");
							$.hideIndicator();
						}else{
							//$("#custCode").html(json.CUSTOMERCODE);
							//$("#custName").html(json.CUSTOMERNAME);
							//$("#custProp").html(json.CUSTPROP);
							//$("#address").html(json.ADDRESS);
							//$("#phone").html(json.PHONE);
							//$("#acctNo").html(json.ACCOUNTID);
							location.href = "index.php?m=qzgdwl&a=ywyLogin";
						}
				  }
			});	
		});
}

function changeInput(input_type)
{
	  if(input_type=="1")
	  {
	  	  document.getElementById('password').style.display="block";
	  	  document.getElementById('linkphone').style.display="none";
		  $("input[name='cust_name']")[0].value = "%";
	  }
	  else
	  {
		  $("input[name='cust_name']")[0].value = "";
	      document.getElementById('linkphone').style.display="block";
	  	  document.getElementById('password').style.display="none";
		  $("input[name='pwd']")[0].value = "";
	  }
}

function login(){
	if(BASEtrim(document.getElementsByName("boss_no")[0].value) == ""){
		$.alert('请输入BOSS账号', '错误!');
		return;
	}
	else if(BASEtrim(document.getElementsByName("boss_name")[0].value) == ""){
		$.alert('请输入姓名', '错误!');
		return;
	}
	else if(BASEtrim(document.getElementsByName("phone")[0].value) == ""){
		$.alert('请输入手机号码', '错误!');
		return;
	}	
	$.showIndicator();
	$.ajax({
		  type:'post',
		  url: "index.php?m=qzgdwl&a=ywyLoginConfirm",
		  context: document.body,
		  dataType:'json',  
		  data:"boss_no="+BASEtrim(document.getElementsByName("boss_no")[0].value)+"&boss_name="+BASEtrim(document.getElementsByName("boss_name")[0].value)+"&phone="+BASEtrim(document.getElementsByName("phone")[0].value),
		  success: function(json){
				if(json.CODE == "99"){
					$.alert('信息不匹配，请重新输入', '错误!');
					$.hideIndicator();
				}else if(json.CODE == "1"){
					$.alert('开始使用系统', '登记成功!', function () {
						location.href = "index.php?m=qzgdwl&a=ywyIndex" ;
					});
				}else if(json.CODE == "2"){
					$.alert('该账号已登记，请重新确认', '错误!');
				}else if(json.CODE == "0"){
					$.alert('输入超时，请退出重新进入', '错误!');
				}
		  }
	});	
	$.hideIndicator();
}


function queryByCardno()
{
	if(BASEtrim(document.getElementsByName("cust_name")[0].value)=="")
	  {
	  	  $.alert('请输入客户姓名', '错误!');
	  	  return ;
	  }
	  if(BASEtrim(document.getElementsByName("cardno")[0].value)=="")
	  {
	  	  $.alert('请输入智能卡号', '错误!');
		  //document.getElementById('result').innerHTML="请输入智能卡号!";
	  	  return ;
	  }
	  $.showIndicator();
	  $.ajax({
	  	    type:'post',
				  url: "index.php?m=qzgdwl&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"cardno="+BASEtrim(document.getElementsByName("cardno")[0].value)+"&cust_name="+BASEtrim(document.getElementsByName("cust_name")[0].value),
				  success: function(data){
						$("#result").html(data);
						$.hideIndicator();
				  }
			});		
}
function queryByAddress()
{
	  if(BASEtrim(document.getElementsByName("cust_name")[0].value)=="")
	  {
	  	  $.alert('请输入客户姓名', '错误!');
	  	  return ;
	  }
	  if(BASEtrim(document.getElementsByName("address")[0].value)=="")
	  {
	  	  $.alert('请输入客户地址', '错误!');
	  	  return ;
	  }
	  $.showIndicator();
	  $.ajax({
	  	    type:'post',
				  url: "index.php?m=qzgdwl&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"cust_name="+BASEtrim(document.getElementsByName("cust_name")[0].value)+"&address="+BASEtrim(document.getElementsByName("address")[0].value),
				  success: function(data){
						  $("#result").html(data);
						  $.hideIndicator();
				  }
			});		
}

function queryByCertno()
{
	if(BASEtrim(document.getElementsByName("cust_name")[0].value)=="")
	  {
	  	  $.alert('请输入客户姓名', '错误!');
	  	  return ;
	  }
	  if(BASEtrim(document.getElementsByName("cert_no")[0].value)=="")
	  {
		  $.alert('请输入身份证号', '错误!');
	  	  //document.getElementById('result').innerHTML="请输入身份证号!";
	  	  return ;
	  }
	  $.showIndicator();
	  $.ajax({
	  	    type:'post',
				  url: "index.php?m=qzgdwl&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"&cert_no="+BASEtrim(document.getElementsByName("cert_no")[0].value)+"&cust_name="+BASEtrim(document.getElementsByName("cust_name")[0].value),
				  success: function(data){
						  $("#result").html(data);
						  $.hideIndicator();
				  }
			});		
}
function queryByStbno()
{
	if(BASEtrim(document.getElementsByName("cust_name")[0].value)=="")
	  {
	  	  $.alert('请输入客户姓名', '错误!');
	  	  return ;
	  }
	  if(BASEtrim(document.getElementsByName("stbno")[0].value)=="")
	  {
	  	  $.alert('请输入机顶盒号', '错误!');
		  //document.getElementById('result').innerHTML="请输入机顶盒号!";
	  	  return;
	  }
	  $.showIndicator();
	  $.ajax({
	  	    type:'post',
				  url: "index.php?m=qzgdwl&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"stbno="+BASEtrim(document.getElementsByName("stbno")[0].value)+"&cust_name="+BASEtrim(document.getElementsByName("cust_name")[0].value),
				  success: function(data){
						  $("#result").html(data);
						  $.hideIndicator();
				  }
			});		
}
function queryByMac()
{
	if(BASEtrim(document.getElementsByName("cust_name")[0].value)=="")
	  {
	  	  $.alert('请输入客户姓名', '错误!');
	  	  return ;
	  }
	  if(BASEtrim(document.getElementsByName("mac")[0].value)=="")
	  {
	  	  $.alert('请输入MAC地址', '错误!');
		  //document.getElementById('result').innerHTML="请输入MAC地址!";
	  	  return ;
	  }
	  $.showIndicator();
	  $.ajax({
	  	    type:'post',
				  url: "index.php?m=qzgdwl&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"mac="+BASEtrim(document.getElementsByName("mac")[0].value).toUpperCase()+"&cust_name="+BASEtrim(document.getElementsByName("cust_name")[0].value),
				  success: function(data){
						  $("#result").html(data);
						  $.hideIndicator();
				  }
			});	
}

function queryByPhone()
{
	if(BASEtrim(document.getElementsByName("cust_name")[0].value)=="")
	  {
	  	  $.alert('请输入客户姓名', '错误!');
	  	  return ;
	  }
	  if(BASEtrim(document.getElementsByName("phone")[0].value)=="")
	  {
	  	  $.alert('请输入联系电话', '错误!');
		  //document.getElementById('result').innerHTML="请输入联系电话!";
	  	  return ;
	  }
	  $.showIndicator();
	  $.ajax({
	  	    type:'post',
				  url: "index.php?m=qzgdwl&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"phone="+BASEtrim(document.getElementsByName("phone")[0].value)+"&cust_name="+BASEtrim(document.getElementsByName("cust_name")[0].value),
				  success: function(data){
						  $("#result").html(data);
						  $.hideIndicator();
				  }
			});	
}

function queryByCustno1()
{
	 if(BASEtrim(document.getElementsByName("cust_no")[0].value)=="")
	 {
	     return;
	 }
	 
	 $.ajax({
	  	    type:'post',
				  url: "index.php?m=qzgdwl&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"resulttype=1&cust_no="+BASEtrim(document.getElementsByName("cust_no")[0].value),
				  success: function(data){
						  $("#msg1").html(data);
				  }
			});	
}
function queryByCustno2()
{
	 if(BASEtrim(document.getElementsByName("member_cust_no")[0].value)=="")
	 {
	     return;
	 }
	 $.ajax({
	  	    type:'post',
				  url: "index.php?m=qzgdwl&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"resulttype=1&cust_no="+BASEtrim(document.getElementsByName("member_cust_no")[0].value),
				  success: function(data){
						  $("#msg2").html(data);
				  }
			});	
}
function queryByCardno2()
{
	 if(BASEtrim(document.getElementsByName("member_cardno")[0].value)=="")
	 {
	     return;
	 }
	 $.ajax({
	  	    type:'post',
				  url: "index.php?m=qzgdwl&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"resulttype=1&cardno="+BASEtrim(document.getElementsByName("member_cardno")[0].value),
				  success: function(data){
						  $("#msg2").html(data);
				  }
			});	
}


function checkIsRepeat()
{
	  var username = document.getElementsByName("member_no")[0].value;
	  if( username == "" || username == null )
	  {
        alert("会员名不能为空!");
        return;
    }
    
   $.ajax({
	  	    type:'post',
				  url: ctx+"/band/band!checkMember",
				  context: document.body,
				  dataType:'text',
				  data:"member_no="+username,
				  success: function(data){
						  $("#memberRepeat").html(data);
				  }
			});	

}
function save()
{
	  if(BASEtrim(document.form1.member_no.value)=="")
	  {
	  	  alert("请输入会员名!");
	  	  return false;
	  }
	  if(document.form1.member_pwd.value=="")
	  {
	  	  alert("请输入密码!");
	  	  return false;
	  }
	  if(document.form1.member_confirmpwd.value=="")
	  {
	  	  alert("请输入确认密码!");
	  	  return false;
	  }
	  if(BASEtrim(document.form1.member_phone.value)=="")
	  {
	  	  alert("请输入联系电话!");
	  	  return false;
	  }
	  if(document.form1.member_pwd.value!=document.form1.member_confirmpwd.value)
	  {
	  	 alert("密码输入不一致，请重输!");
	  	 document.form1.member_pwd.value="";
	  	 document.form1.member_confirmpwd.value="";
	  	 return false;
	  }
	  return true;
}

function save1()
{
	  if(save())
	  {
	  	  document.form1.submit1.disabled=true;
	      document.form1.submit();
	  }
	  
}

function save2()
{
	 if(save())
	 {
	     if(BASEtrim(document.form1.cust_no.value)=="")
	     {
	         alert("请输入客户编号!");
	         return;
	     }
	     if(document.form1.pwd.value=="")
	     {
	     	   alert("请输入业务密码!");
	         return;
	     }
	     document.form1.submit2.disabled=true;
	     document.form1.submit();
	 }
}

function save3()
{
	 if(save())
	 {
	     if(BASEtrim(document.form1.member_cert_no.value)=="")
	     {
	         alert("请输入证件号!");
	         return;
	     }
	     if(BASEtrim(document.form1.member_cust_no.value)=="")
	     {
	     	   alert("请输入客户编号!");
	         return;
	     }
	     if(BASEtrim(document.form1.member_cust_name.value)=="")
	     {
	     	   alert("请输入客户姓名!");
	         return;
	     }
	     if(BASEtrim(document.form1.member_cardno.value)==""&&BASEtrim(document.form1.member_account.value)=="")
	     {
	     	   alert("请输入智能卡号或宽带账号!");
	         return;
	     }
	     document.form1.submit3.disabled=true;
	     document.form1.submit();
	 }
}

//获取URL的参数值
function GetRequest() { 
	var url = location.search; //获取url中"?"符后的字串 
	var theRequest = new Object(); 
	if (url.indexOf("?") != -1) { 
		var str = decodeURI(url.substr(1)); 
		strs = str.split("&"); 
		for(var i = 0; i < strs.length; i ++) { 
			theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]); 
		} 
	} 
	return theRequest; 
} 
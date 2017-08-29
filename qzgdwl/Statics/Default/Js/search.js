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
function queryByName()
{
	  if(BASEtrim(document.getElementsByName("cust_name")[0].value)=="")
	  {
	  	  document.getElementById('result').innerHTML="请输入姓名!";
	  	  return ;
	  }
	  /*if(BASEtrim(document.getElementsByName("phone")[0].value)=="")
	  {
	  	  document.getElementById('result').innerHTML="请输入联系电话!";
	  	  return ;
	  }*/
	  $.ajax({
	  	    type:'post',
				  url: "index.php?m=index&a=query",
				  context: document.body,
				  dataType:'text',  
				  data:"cust_name="+BASEtrim(document.getElementsByName("cust_name")[0].value),
				  success: function(data){
						  $("#result").html(data);
				  }
			});		
}
function queryByCardno()
{
	  if(BASEtrim(document.getElementsByName("cardno")[0].value)=="")
	  {
	  	  document.getElementById('result').innerHTML="请输入智能卡号!";
	  	  return ;
	  }
	  
	  $.ajax({
	  	    type:'post',
				  url: "index.php?m=index&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"cardno="+BASEtrim(document.getElementsByName("cardno")[0].value),
				  success: function(data){
						$("#result").html(data);
				  }
			});		
}
function queryByAddress()
{
	  if(BASEtrim(document.getElementsByName("address")[0].value)=="")
	  {
	  	  document.getElementById('result').innerHTML="请输入地址!";
	  	  return ;
	  }
	  $.ajax({
	  	    type:'post',
				  url: "index.php?m=index&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"cust_name="+BASEtrim(document.getElementsByName("cust_name")[0].value)+"&address="+BASEtrim(document.getElementsByName("address")[0].value),
				  success: function(data){
						  $("#result").html(data);
				  }
			});		
}

function queryByCertno()
{
	  if(BASEtrim(document.getElementsByName("cert_no")[0].value)=="")
	  {
	  	  document.getElementById('result').innerHTML="请输入身份证号!";
	  	  return ;
	  }
	  $.ajax({
	  	    type:'post',
				  url: "index.php?m=index&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"&cert_no="+BASEtrim(document.getElementsByName("cert_no")[0].value),
				  success: function(data){
						  $("#result").html(data);
				  }
			});		
}
function queryByStbno()
{
	  if(BASEtrim(document.getElementsByName("stbno")[0].value)=="")
	  {
	  	  document.getElementById('result').innerHTML="请输入机顶盒号!";
	  	  return;
	  }
	  
	  $.ajax({
	  	    type:'post',
				  url: "index.php?m=index&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"stbno="+BASEtrim(document.getElementsByName("stbno")[0].value),
				  success: function(data){
						  $("#result").html(data);
				  }
			});		
}
function queryByMac()
{
	  if(BASEtrim(document.getElementsByName("mac")[0].value)=="")
	  {
	  	  document.getElementById('result').innerHTML="请输入MAC地址!";
	  	  return ;
	  }
	  
	  $.ajax({
	  	    type:'post',
				  url: "index.php?m=index&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"mac="+BASEtrim(document.getElementsByName("mac")[0].value),
				  success: function(data){
						  $("#result").html(data);
				  }
			});	
}

function queryByPhone()
{
	  if(BASEtrim(document.getElementsByName("phone")[0].value)=="")
	  {
	  	  document.getElementById('result').innerHTML="请输入联系电话!";
	  	  return ;
	  }
	  
	  $.ajax({
	  	    type:'post',
				  url: "index.php?m=index&a=query",
				  context: document.body,
				  dataType:'text',
				  data:"phone="+BASEtrim(document.getElementsByName("phone")[0].value),
				  success: function(data){
						  $("#result").html(data);
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
				  url: "index.php?m=index&a=query",
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
				  url: "index.php?m=index&a=query",
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
				  url: "index.php?m=index&a=query",
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

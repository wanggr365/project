$(function(){
    $("#second").click(function (){
        sendCode($("#second"));
    });
    v = getCookieValue("secondsremainedps");//获取cookie值
    if(v>0){
        settime($("#second"));//开始倒计时
    }
})
//发送验证码
function sendCode(obj){
	var phone = $(".phone");
	var password1 = $(".password1");
	var password2 = $(".password2");
	if(BASEtrim(phone.val()) == "" || BASEtrim(password1.val()) == "" || BASEtrim(password2.val()) == "") {
		layer.open({
			btn: ['好的'],
			content: '<p style="text-align:center;">提示！<br/>除了验证码，其他信息都要填完！</p>',
			shadeClose: false
		});
		return false;
	}else if(password1.val() != password2.val()){
		layer.open({
			btn: ['好的'],
			content: '<p style="text-align:center;">提示！<br/>密码不一致！</p>',
			shadeClose: false
		});
		return false;
	}else{
		var resultResult = checkPassword(password1.val());
		var result = isPhoneNum(phone.val());
		if(result && resultResult){
			doPostBack('index.php?m=agent&a=agentPsSendSMS',backFunc1,$('#registerForm').serialize());//serialize
		}
	}
}
//将手机利用ajax提交到后台的发短信接口
function doPostBack(url,backFunc,queryParam) {
    $.ajax({
        async : false,
        cache : false,
        type : 'POST',
        url : url,// 请求的action路径
        data:queryParam,
        error : function() {// 请求失败处理函数
        },
        success : backFunc
    });
}
function backFunc1(json){
	json = JSON.parse(json);
    if(json.code == 0){
		layer.open({
			btn: ['好的'],
			content: '<p style="text-align:center;">' + json.msg +'</p>',
			shadeClose: false
		});
		setInterval('https://open.weixin.qq.com/connect/oauth2/authorize?appid=wx1ae00a2623048bf1&redirect_uri=http%3a%2f%2fwww.968816.com.cn%2fqzgdwl%2findex.php%3fm%3dlogin%26a%3dindex&response_type=code&scope=snsapi_base&state=agent%26agentLogin#wechat_redirect',3000);
	}else{
		layer.open({
			btn: ['好的'],
			content: '<p style="text-align:center;">' + json.msg +'</p>',
			shadeClose: false
		});
		if(json.code == 1){			
			$("#second").removeClass("code").addClass('disabled1');
			addCookie("secondsremainedps",60,60);//添加cookie记录,有效时间60s
			settime($("#second"));//开始倒计时
		}
	}
}
//开始倒计时
var countdown;
function settime(obj) { 
    countdown=getCookieValue("secondsremainedps");
    if (countdown == 0) { 
        obj.removeAttr("disabled");    
        obj.val("获取验证码"); 
		obj.removeClass("disabled1").addClass('code').
        return;
    } else { 
        obj.attr("disabled", true); 
        obj.val("重新发送(" + countdown + ")"); 
        countdown--;
        editCookie("secondsremainedps",countdown,countdown+1);
    } 
    setTimeout(function() { settime(obj) },1000) //每1000毫秒执行一次
} 
//校验手机号是否合法
function isPhoneNum(phone){
    var myreg = /^(((13[0-9]{1})|(17[7-7]{1})|(15[0-9]{1})|(18[0-9]{1}))+\d{8})$/; 
    if(!myreg.test(phone)){ 
        layer.open({
			btn: ['好的'],
			content: '<p style="text-align:center;">提示！<br/>手机号码格式不正确！</p>',
			shadeClose: false
		});
		return false;
    }else{
        return true;
    }
}

//发送验证码时添加cookie
function addCookie(name,value,expiresHours){ 
    var cookieString=name+"="+escape(value); 
    //判断是否设置过期时间,0代表关闭浏览器时失效
    if(expiresHours>0){ 
        var date=new Date(); 
        date.setTime(date.getTime()+expiresHours*1000); 
        cookieString=cookieString+";expires=" + date.toUTCString(); 
    } 
        document.cookie=cookieString; 
} 
//修改cookie的值
function editCookie(name,value,expiresHours){ 
    var cookieString=name+"="+escape(value); 
    if(expiresHours>0){ 
      var date=new Date(); 
      date.setTime(date.getTime()+expiresHours*1000); //单位是毫秒
      cookieString=cookieString+";expires=" + date.toGMTString(); 
    } 
      document.cookie=cookieString; 
} 
//根据名字获取cookie的值
function getCookieValue(name){ 
      var strCookie=document.cookie; 
      var arrCookie=strCookie.split("; "); 
      for(var i=0;i<arrCookie.length;i++){ 
        var arr=arrCookie[i].split("="); 
        if(arr[0]==name){
          return unescape(arr[1]);
          break;
        }else{
             return ""; 
             break;
         } 
      } 
       
}

function checkPassword(password){
	var reg = /^[A-Za-z0-9]{6,20}$/;
	var result = reg.test(password);
	if(result){
		return true;
	}else{
		layer.open({
			btn: ['好的'],
			content: '<p style="text-align:center;">提示！<br/>密码格式不正确！</p>',
			shadeClose: false
		});
		return false;
	}
}

function checkCertNo(ID_card) {//身份证校验
  var bReturn =false;
  ID_card=ID_card.trim();
  var len=ID_card.length;
  if (len==15){
	 var birthday=ID_card.substr(6,6);
	 var date="19"+birthday.substr(0,2)+"-"+birthday.substr(2,2)+"-"+birthday.substr(4,2);
	 if (IsDate(date)==true){
	    bReturn=checkNum(ID_card);
	 }

  }else  if (len==18){
  	 var birthday=ID_card.substr(6,8);
	 var date=birthday.substr(0,4)+"-"+birthday.substr(4,2)+"-"+birthday.substr(6,2);;
	 if (IsDate(date)==true){
	    bReturn=checkNum(ID_card.substr(0,17));
	 }

  }
  if(!bReturn){
	layer.open({
		btn: ['好的'],
		content: '<p style="text-align:center;">提示！<br/>身份证格式不正确！</p>',
		shadeClose: false
	});
  }
  return bReturn;

}
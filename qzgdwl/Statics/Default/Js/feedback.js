/*!
 * ======================================================
 * FeedBack Template For MUI (http://dev.dcloud.net.cn/mui)
 * =======================================================
 * @version:1.0.0
 * @author:cuihongbao@dcloud.io
 */
(function() {
	var index = 1;
	var size = null;
	var imageIndexIdNum = 0;
	var starIndex = 0;
	var feedback = {
		faultType: document.getElementById('faultType'), 
		question: document.getElementById('question'), 
		contact: document.getElementById('contact'), 
		address: document.getElementById('address'), 
		submitBtn: document.getElementById('submit')
	};
	
	/**
	 *提交成功之后，恢复表单项 
	 */
	feedback.clearForm = function() {
		feedback.faultType.innerHTML = '';
		feedback.question.value = '';
		feedback.contact.value = '';
		feedback.address.value = '';
		index = 0;
		size = 0;
		imageIndexIdNum = 0;
		starIndex = 0;
	};
	
	feedback.submitBtn.addEventListener('tap', function(event) {
	alert(123);
		/*if (feedback.question.value == '' ||
			(feedback.contact.value != '' &&
				feedback.contact.value.search(/^(\w+((-\w+)|(\.\w+))*\@[A-Za-z0-9]+((\.|-)[A-Za-z0-9]+)*\.[A-Za-z0-9]+)|([1-9]\d{4,9})$/) != 0)) {
			return mui.toast('信息填写不符合规范');
		}*/
		if(feedback.faultType.innerHTML == ""){
			return mui.toast('故障类型不能为空');
		}else if(feedback.contact.value == ""){
			return mui.toast('手机号不能为空');
		}
		var btnArray = ['取消', '确定'];
		mui.confirm('手机号为' + feedback.contact.value , '提交前请确认', btnArray, function(e) {
			if (e.index == 1) {
				$.ajax({
				type:'post',
				  url: "index.php?m=qzgdwl&a=qzgdwlSaveFault&unionid=" + unionid,
				  context: document.body,
				  dataType:'json',  
				  data:"fault_type="+BASEtrim(feedback.faultType.innerHTML)+"&fault_detail="+feedback.question.value+"&fault_phone="+BASEtrim(feedback.contact.value)+"&fault_address="+feedback.address.value+"&fault_pic="+filename,
				  success: function(json){
						if(json.code == "0"){
							mui.alert(json.msg, function() {
							});
						}else{
							mui.alert("提交失败！", function() {
							});
						}
				  }
				});	
			} else {
				feedback.contact.focus();
				return false;
			}
		})
		
	}, false)
	
	
  	 
})();


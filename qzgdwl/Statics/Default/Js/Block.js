
function Block( dom ){
	this.dom = dom; //dom
	this.parentW = this.dom.parentNode.clientWidth;
	this.parentH = this.dom.parentNode.clientHeight;
	this.scale = 1.5; //黑块 宽高比
	this.h = parseInt(this.parentW/3*this.scale);
	this.top = -this.h;
	this.speed = 6; //速度
	this.maxSpeed = isAndroid?8:12;
	this.timer = null; //定时器
	this.state = true; //游戏判断
	this.sum = 0; //分数
	this.timerGame = null; //定时器
	this.times = 30; //倒计时间
	this.randTime = Math.ceil(Math.random()*5000);
	this.value = 20;//钱
	this.money = 0;
	this.scaleH = Math.floor(672/(this.dom.parentNode.clientHeight)* 100) / 100;	
	
	
}

Block.prototype.init = function(){

	var _t = this;
	var s = 0;
	var arrMoney = [];
	var type = [1,5,6,7,8]
	for(var i=0;i<100;i++){
		arrMoney[i] = 5;
	}

	_t.zsy();
	_t.mark();
	switch(_t.value){
		case 10:
			for(var i=0;i<60;i++){
				Math.random() >= 0.3?arrMoney[i]=type[Math.floor(Math.random()*4+1)]:arrMoney[i]=1;
			}
			break;
		case 20:
			for(var i=0;i<60;i++){
				Math.random() >= 0.6?arrMoney[i]=type[Math.floor(Math.random()*4+1)]:arrMoney[i]=1;
			}
			break;
		case 50:
			for(var i=0;i<100;i++){
				Math.floor(Math.random()*2+1) == 1?arrMoney[i]=type[Math.floor(Math.random()*4+1)]:arrMoney[i]=1;
			}
			break;
		case 100:
			for(var i=0;i<100;i++){
				arrMoney[i] = 1;
			}
			break;
	}

	var clickEvent = "ontouchstart" in document.documentElement ? "touchstart" : "click";

	_t.dom.addEventListener(clickEvent,function(e){

		if(!_t.state){
			return false;
		}
		e = e || window.event;
		var target = e.target ||e.srcElement;
		if(target.className.indexOf('block')!=-1){//正确点击红包
			
			target.className = 'cell block_click'+arrMoney[_t.sum];
			arrMoney[_t.sum] <= 4?_t.money++:true;
			_t.sum++;
			//log(target)
		}else{//未正确点击红包-停止
			// _t.state = false;
			// clearInterval(_t.timer);
			// _t.end();
			// return false;
		}
	});
}

//创建一行，，，
Block.prototype.addRow = function(){

	var oRow = document.createElement('div');
	oRow.className = 'row';
	oRow.style.height = this.h + 'px';

	var cells = ['cell','cell','cell','cell'];
	var s = Math.floor(Math.random()*4);
	cells[s] = "cell block";
	//cells[s>0?s-1:s+1] = "cell block";
	var oCell = null;

	for (var i=0; i<4; i++) {
		oCell = document.createElement('div');
		oCell.className = cells[i];
		oRow.appendChild( oCell );
		
	}

	var fChild = this.dom.firstChild;
	if( fChild == null ){
		this.dom.appendChild(oRow);
		
	}else{
		this.dom.insertBefore(oRow , fChild);
	
	}
}

function log(obj){
	console.log(obj);
}
//删除一行，，，
Block.prototype.delRow = function(){
	//log(1);
	this.dom.removeChild( this.dom.childNodes[ this.dom.childNodes.length-1 ] );
}


//判断什么时候拉回top 什么时候提升速度，，，什么时候停止
Block.prototype.judge = function(){

	var _t = this;

	if(_t.top >= 0){
		_t.top = -this.h;
		_t.dom.style.top = _t.top +'px';
		_t.addRow();
	}

	var rows = _t.getEleByClassName('row','div',_t.dom);
	for (var i=0; i<rows.length; i++) {
		if (rows[i].offsetTop >= _t.parentH +_t.h) {
			_t.delRow();
		}
	}
	//alert();
	_t.speed = parseInt((_t.sum + 1)*2/_t.scaleH); //根据点的白块总数提升速度
	if( _t.speed >=_t.maxSpeed ){ _t.speed = _t.maxSpeed; } //最大速度 

	var blocks = _t.getEleByClassName('block','div',_t.dom);
	for (var j=0; j<blocks.length; j++){
		if ( blocks[j].offsetTop >= _t.parentH ){
			//_t.state = false;
			//clearInterval(_t.timer);
			//_t.end();
		}
	}
}

Block.prototype.getEleByClassName =  function(className,tagName,context){
	context = context || document;
	tagName = tagName || '*';
	if(document.getElementsByClassName) {
		return context.getElementsByClassName(className);
	} else {
		var el = new Array();
		var aEle = context.getElementsByTagName(tagName);
		var re=new RegExp('\\b'+className+'\\b', 'i');
 		var i=0;
 		for(i=0;i<aEle.length;i++)
		{
			if(re.test(aEle[i].className))
			{
				aResult.push(aEle[i]);
			}
		}
		return aResult;
	}
}

//移动
Block.prototype.move = function(){
	//alert(this.speed);
	this.top += this.speed;
	this.dom.style.top = this.top +'px';
	//this.dom.style.left = this.top +'px';
	if(this.top >= this.parentW){
		//this.dom.style.left = '0px';
	}else{
		//this.dom.style.left = this.top +'px';
	}
	//alert(this.dom.style.left);
}

//分数
Block.prototype.mark = function(){
	var oMark = document.createElement("div");
	oMark.className = "mark";
	oMark.innerHTML = this.times;
	this.dom.parentNode.appendChild(oMark);
}

//元素自适应
Block.prototype.zsy = function(){
	var _t = this;
	if("ontouchstart" in document.documentElement){
		_t.dom.parentNode.className = "ph-main";
	}
}

//游戏开始
Block.prototype.start = function(){

	var _t = this;
	
	_t.timerGame = setInterval(function(){
		$('.mark').html(_t.times);
		_t.times--;
		if(_t.times == 0){		
			_t.state = false;
			$('.mark').html(_t.times);
			clearInterval(_t.timer);
			clearInterval(_t.timerGame);		
			$("#loadingToast .weui_toast_content").html('数据提交中<br>请稍后');
			$("#loadingToast").show();		
			var t = setTimeout("block.end()",_t.randTime);
		}
	},1000)
	
	for( var i=0; i<4; i++ ){
		_t.addRow();
	}

	_t.timer = setInterval(function(){
		_t.move();
		_t.judge();
	},30);
}

//function


//游戏结束  
Block.prototype.end = function(){
		
	
	var _t = this;
	var currentAjax = null;
	currentAjax = $.ajax({
		type:'post',
		url: "index.php?m=qhb&a=qhbSaveMoney",
		context: document.body,
		timeout : 10000,
		dataType:'json',
		data:"money=" + _t.money + "&unionid=" + document.getElementById("unionid").value + "&number=" + _t.sum,
		error: function () {	
			$("#loadingToast").hide();	
			$("#toast_error .weui_toast_content").html('网络异常<br>请退出重试');
			$('#toast_error').show();
			
		},
		success: function(json){			
			$("#loadingToast").hide();	
			if( !document.getElementById("againMask") ){
				var againMask = document.createElement('div');
				againMask.className = 'again-mask';
				againMask.id = 'againMask';				
				_t.dom.parentNode.appendChild(againMask);
			}else{
				var againMask = document.getElementById("againMask");
				againMask.style.display = "block";
				var againSum = document.getElementById("againSum");			
			}
			
			/***************判断****************/

			if(0<_t.sum<=10){
				againMask.innerHTML =
				'<div id="gove" ><h2 id="againSum">只抢了'+_t.money+'元红包</h2><h1>难道是大神没附体？</h1><span id="againStart"><img src="Statics/Default/Images/qhb/e2.png"></span><div>';

			}

			if(_t.sum>10){
				againMask.innerHTML =
				'<div id="gove" ><h2 id="againSum">抢到了'+_t.money+'元红包</h2><h1>果然是大神附体!</h1><span id="againStart"><img src="Statics/Default/Images/qhb/e2.png"></span><div>';

			}
			setTimeout(function(){
				window.location.href = "index.php?m=qhb&a=qhbIndex";
			},1500);
			
			var againStart = document.getElementById("againStart");
			againStart.onclick = function(){
				window.location.href = "index.php?m=qhb&a=qhbIndex";
			}
		},
		complete : function(XMLHttpRequest,status){ //请求完成后最终执行参数
　　　　if(status=='timeout'){//超时,status还有success,error等值的情况

 　　　　　 currentAjax.abort(); //取消请求
　　　　　  $("#loadingToast").hide();	
			$("#toast_error .weui_toast_content").html('网络异常<br>请退出重试！');
			$('#toast_error').show();
			

　　　　}
　　}
	});	
	
}

//游戏重来
Block.prototype.again = function(){
	this.parentW = this.dom.parentNode.clientWidth;
	this.parentH = this.dom.parentNode.clientHeight;
	this.scale = 1.5; //黑块 宽高比
	this.h = parseInt(this.parentW/3*this.scale);
	this.top = -this.h;
	this.speed = 6; //速度
	this.timer = null; //定时器
	this.timerGame = null; //定时器
	this.state = true; //游戏判断
	this.sum = 0; //分数
	this.times = 30;

	var _t = this;
	_t.dom.innerHTML = "";
	_t.getEleByClassName('mark','div')[0].innerHTML = _t.times;
	_t.start();
}
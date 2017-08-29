/*
 *
 * 修改历史：
 *          1)2004-07-22      蓝敏
 *           加方法
 *              checkInteger(pNumber,pIntLen)
 *              判断在数字pNumber里面整数位数是否在设定的范围pIntLen之内
 *          2)2004-08-11      蓝敏
 *            修改方法
 *               function IsNotInt(theInt)
 ×               判断数字是否为整数
 */																   

 <!--
function getRightNo(pTextBox,pStartBit,pEndBit){
	if(pTextBox.value!="" && pStartBit!="" && pEndBit !="")
		if(pTextBox.value.length>=parseInt(pEndBit,10) && parseInt(pEndBit,10)>=parseInt(pStartBit,10))
			pTextBox.value=pTextBox.value.substring(pStartBit-1,pEndBit);

}
 // tipStrVal:中文提示名
 //	str：  待检查的字符串
function isValidPKStr(tipStrVal,str){
     if(str==null || BASEtrim(str)=="") {
		alert(tipStrVal+"不能为空！");
		return false;
	 }
     len = str.length;
	 var tipStr="";
	 for (var i = 0;i < len;i++){
	   if (str.charAt(i)=="'"){
		   tipStr=tipStr+"含有非法字符:' \n";
	   }else if (str.charAt(i)=="\""){
		   tipStr=tipStr+"含有非法字符:\" \n";
	   }else if (str.charAt(i)=="<"){
		   tipStr=tipStr+"含有非法字符:< \n";
	   }else if (str.charAt(i)==">"){
		   tipStr=tipStr+"含有非法字符:> \n";
	   }else if (str.charAt(i)=="&"){
		   tipStr=tipStr+"含有非法字符:& \n";
	   }else if (str.charAt(i)=="/"){
		   tipStr=tipStr+"含有非法字符:/ \n";
	   }else if (str.charAt(i)=="\\"){
		   tipStr=tipStr+"含有非法字符:\\  \n";
	   }else if (str.charAt(i)=="#"){
		   tipStr=tipStr+"含有非法字符:#  \n";
	   }else if (str.charAt(i)=="+"){
		   tipStr=tipStr+"含有非法字符:+  \n";
	   }
	   if ( str.charCodeAt(i)>127 ){
	   	  tipStr=tipStr+"含有非法字符:"+str.charAt(i)+" \n";
	   }
	 }
	 if (tipStr!=""){
	    alert(tipStrVal+"\n"+tipStr);
		return false;
	 }
     return true;
  }

//trim()字符
function BASEtrim(str){
	  if (str!=""){
	  lIdx=0;
//	  if (document.all==null)
//	  	rIdx=str.length();
//	  else
		rIdx=str.length;
	  if (BASEtrim.arguments.length==2)
	    act=BASEtrim.arguments[1].toLowerCase();
	  else
	    act="all"
      for(var i=0;i<str.length;i++){
	  	thelStr=str.substring(lIdx,lIdx+1);
		therStr=str.substring(rIdx,rIdx-1);
        if ((act=="all" || act=="left") && thelStr==" "){
			lIdx++;
        }
        if ((act=="all" || act=="right") && therStr==" "){
			rIdx--;
        }
      }
	  if (document.all==null){
		str=str.substring(lIdx,rIdx);
	  }
	  else{
	    str=str.slice(lIdx,rIdx);
	  }

	  }
      return str;
}

//获取实际字符串长度
function getRealLen(paramVal){
	  var real_len=0;

 	  for(i=0;i<paramVal.length;i++){
		if (paramVal.charCodeAt(i)>127 || paramVal.charCodeAt(i) < 0){
			real_len=real_len+2;
		}else{
			real_len++;
		}
	  }

	  return(real_len);
 }


 //获取业务操作类型 (1:数字电视；2:宽频上网)
 function getBusinessType(servId){

    if (servId==null || servId==""){
		return "1" ;
    } 

	if (servId.substring(0,1) == "1" ){
		return "1";
	}else if (servId.substring(0,1) == "8" ){
		return "2";
	}

 }



// 判断在数字pNumber里面整数位数是否在设定的范围pIntLen之内
// True 在范围之内
// False 不在
function checkInteger(pNumber,pIntLen){
	//如果传入参数不是数字，退出
	if(BASEisNotNum(pNumber))
		return false;

  var intLen=0; //正数长度
	for(var i=0;i<pNumber.length;i++){
		oneNum=pNumber.substring(i,i+1);
    if (oneNum!='.')
			intLen=intLen+1;
		else
			break;
	}
	if(intLen<=pIntLen)
		return true;
	else
		return false;
}

//是否数字
function BASEisNotNum(theNum){

	if (BASEtrim(theNum)=="")
		return true;

	for(var i=0;i<theNum.length;i++){
	    oneNum=theNum.substring(i,i+1);
		if (oneNum!='.')
		{
			if (oneNum<"0" || oneNum>"9")
			{
			  return true;
			 }
		}
    }
	return false;
}


//分解数字
function parseNum(theNum){
  if (theNum.substring(0,1)==0)
    theNum=theNum.substring(1)
  return theNum
}

//是否整数
function BASEisNotInt(theInt){
	theInt=BASEtrim(theInt)
	if ((theInt.length>1 && theInt.substring(0,1)=="0") || BASEisNotNum(theInt)){
		return true
	}
	return false
}

//是否整数
//True  不是整数
//False  是整数
function IsNotInt(theInt){
	theInt=BASEtrim(theInt);
	if (BASEtrim(theInt)=="")
		return true;

	if(theInt.substring(0,1)=="0")
		return false;

	for(var i=0;i<theInt.length;i++){
		oneNum=theInt.substring(i,i+1);
		if (oneNum<"0" || oneNum>"9")
			return true;
  }

	return false;
}

//是否float
function BASEisNotFloat(theFloat){
	len=theFloat.length
	dotNum=0
	if (len==0)
		return true
	for(var i=0;i<len;i++){
	    oneNum=theFloat.substring(i,i+1)
		if (oneNum==".")
			dotNum++
        if ( ((oneNum<"0" || oneNum>"9") && oneNum!=".") || dotNum>1)
          return true
    }
	if (len>1 && theFloat.substring(0,1)=="0"){
		if (theFloat.substring(1,2)!=".")
			return true
	}
	return false
}

//分解ymd
function parseYMD(theYear,theMonth,theDay) {
  theYear=parseNum(theYear)
  theMonth=parseNum(theMonth)
  theDay=parseNum(theDay)
  if ((theYear < 1900) || (theYear > 3000)){
    return 1
  }
  if (theMonth < 1 || theMonth > 12){
    return 2
  }
  if ((theMonth==1 || theMonth==3 || theMonth==5 || theMonth==7 || theMonth==8 || theMonth==10 || theMonth==12) &&
      (theDay <1 || theDay > 31)
     ){
    return 3
  }
  if ((theMonth==4 || theMonth==6 || theMonth==9 || theMonth==11) &&
      (theDay <1 || theDay > 30)
     ){
    return 3
  }
  if (theYear%400==0 || (theYear%4==0 && theYear%100!=0)){  //闰年
    if (theMonth==2 && (theDay <1 || theDay > 29) )
      return 3
  }
  else  //平年
    if (theMonth==2 && (theDay <1 || theDay > 28) )
      return 3
  return 0
}

//是否日期
function isInvalidDate(theDate,separator){

  default_style=1;
  if (theDate.length>10 || theDate.length<8)
    return true
  idx1=theDate.indexOf(separator)
  if (idx1==-1)
    return true
  idx2=theDate.indexOf(separator,idx1+1)
  if (idx2==-1)
    return true
  if (isInvalidDate.arguments.length>2)
  	default_style=isInvalidDate.arguments[2]
  if (default_style<1 || default_style>9){
  	alert("传入参数有误！请检查。")
	return true
  }
  if (default_style==1){
  theYear=theDate.substring(0,idx1)
  theMonth=theDate.substring(idx1+1,idx2)
  theDay=theDate.substring(idx2+1)
  }
  if (default_style==2){
  theMonth=theDate.substring(0,idx1)
  theDay=theDate.substring(idx1+1,idx2)
  theYear=theDate.substring(idx2+1)
  }

  if (theDay.length>2)
    return true

  if (BASEisNotNum(theYear) || BASEisNotNum(theMonth) || BASEisNotNum(theDay))
  {

	return true;

	}
  else{
	if (parseYMD(theYear,theMonth,theDay)>0)
	{

		return true;

	}
	else
	{
		return false;
	}
  }
}

//alert
function BASEalert(theText,notice){
	  alert(notice)
	  theText.focus()
	  theText.select()
	  return false
}

//check_email
function Check_Email(string){
	var str_len = string.length;
	if (str_len<=5){
	   return true
    }
	/*
	for(i=0;i<str_len;i++){
	    if (string.charCodeAt(i)>127){
		return true
		}
	}
	*/
	if (string.indexOf("@")<2){
	    return true
    }
    if (string.indexOf("@")==(str_len-1)){
	    return true
    }
    if (string.indexOf(".")==-1){
	    return true
    }
	if (string.indexOf(":")!=-1){
	    return true
	}
	return false;
}

//是否选
function isChecked(theObject){
	var i;
	if (theObject.length==null){
		if(theObject.checked==true){
			return true;
		}
	}
	else{
	for(i=0;i<theObject.length;i++){
		if(theObject[i].checked==true){
			return true;
		}
	}
	}

	return false;
}


//检查日期
function IsDate(sdate)
{
    var yyyy='';
    var mm='';
    var dd='';
    var s1=/\d{4}[/]\d{2}[/]\d{2}/;    //格式:yyyy/mm/dd
    var s2=/\d{4}[-]\d{2}[-]\d{2}/;    //格式:yyyy-mm-dd

		if(BASEtrim(sdate)=="")
			return true;
		if(sdate.length!=10)
			return false;

    ok1=s1.exec(sdate);
    ok2=s2.exec(sdate);
    if(!ok1 && !ok2){
    	return false;
    }else{
    	yyyy=sdate.substring(0,4);
        mm=sdate.substring(5,7);
        dd=sdate.substring(8,10);
        if(mm<0||mm>12){
          return false;
        }
        else if(dd<0||dd>31){
          return false;
        }
        else if(!chkDate(yyyy,mm,dd)) {
	  return false;
	}
        else return true;
    }
}
//检查日期和时间
function IsDateTime(sdate)
{
    var yyyy='';
    var mm='';
    var dd='';
    var s=/\d{2}[:]\d{2}/;    //格式HH:MM

		if(BASEtrim(sdate)=="")
			return true;
		if(sdate.length!=16)
			return false;

	if(IsDate(sdate.substring(0,10)))
	{
		var hh = sdate.substring(11,13);
		var mm = sdate.substring(14,16);
		if(hh>=0 && hh<=23 && mm>=0 && mm<=59)
			return true;
		else
			return false;
	}
	else
		return false;
}
function chkDate(year,month,day)
{
	var arDay;
	arDay = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31)
	if (parseInt(month) == 2)
	{
		if (parseInt(year) % 4 == 0)
		{
			if (parseInt(day) > 29)
			{
				return false;
			}
		}
		else if (parseInt(day) > 28)
		{
			return false;
		}
	}
	else
	{
		switch(month)
		{
			case '08':
				month='8';
				break;
			case '09':
				month='9'
				break;
		}
		if (parseInt(day) > arDay[parseInt(month)-1])
		{
			return false;
		}
	}
	return true;
}

//检查日期
function CheckDate(StrDate){
	var StrYear,StrMonth,StrDay,v
	StrYear=StrDate.substring(0,4)
	StrMonth=StrDate.substring(5,7)
	StrDay=StrDate.substring(8,10)

	if ((StrDate.substring(4,5)!="-") || (StrDate.substring(7,8)!="-")){
		window.alert("日期格式应为yyyy-mm-dd!")
		return false;
	}
	if(StrDate.length == 0){
		window.alert("请输入日期！");
		return false;
	}
	v = parseNum(StrYear);
	if(isNaN(v)){
		window.alert("年份必须为数字！");
		return false;
	}
	if(v<1900){
		window.alert("年份必须>1900");
		return false;
	}

	v = parseNum(StrMonth);
	if(isNaN(v)){
		window.alert("月份必须为数字！");
		return false;
	}
	if(v < 1 || v > 12){
		window.alert("月份必须在1-12之间！");
		return false;
	}
	if(StrDay.length == 0){
		window.alert("请输入日期！");
		return false;
	}
	v = parseNum(StrDay);
	if(isNaN(v)){
		window.alert("日期必须为数字！");
		return false;
	}
	if(v < 1 || v > 31){
		window.alert("日期必须在1-31之间！");
		return false;
	}
	return true;
}


//全选
function SelectAll(srcSelect) {
	var i;
	for (i=0;i<srcSelect.options.length;i++){
		if (srcSelect.options[i].value=="")
			srcSelect.options[i].selected=false;
		else
			srcSelect.options[i].selected=true;
	}

}
//删除提示
function DoDel(strURL){
	if (confirm("您确定执行删除？")==true){
		window.location=strURL;
	}

}
function AddMyFavor(strURL){
	window.open(strURL,"AddFavor","height=400,width=600,scrollbars=yes,toolbar=no,menubar=no,location=no,resizable=yes");
	return;
}
function OpenNewWin(strURL){
	window.open(strURL,"New","height=400,width=600,scrollbars=yes,toolbar=no,menubar=no,location=no,resizable=yes");
	return;
}

//日期检查
function CheckDate1(StrDate){
	var StrYear,StrMonth,StrDay,v
        if (StrDate.length!=10){
          alert("日期格式应为yyyy-mm-dd!");
          return false;
        }
	StrYear=StrDate.substring(0,4)
	StrMonth=StrDate.substring(5,7)
	StrDay=StrDate.substring(8,10)

	if(StrDate.length == 0){
		window.alert("请输入日期！");
		return false;
	}
	if (StrDate.substring(4,5)!=""){
		if (StrDate.substring(4,5)!="-"){
			window.alert("日期格式应为yyyy-mm-dd!")
			return false;
		}
	}
	if (StrDate.substring(7,8)!=""){
		if (StrDate.substring(7,8)!="-"){
			window.alert("日期格式应为yyyy-mm-dd!")
			return false;
		}
	}

	v = parseNum(StrYear);
	if(isNaN(v)){
		window.alert("年份必须为数字！");
		return false;
	}
	if(v<1900){
		window.alert("年份必须>1900");
		return false;
	}

	if (StrMonth.length!=0){
	if (StrMonth.length!=2){
		window.alert("日期格式应为yyyy-mm-dd!")
		return false;
	}
	v = parseNum(StrMonth);
	if(isNaN(v)){
		window.alert("月份必须为数字！");
		return false;
	}
	if(v < 1 || v > 12){
		window.alert("月份必须在1-12之间！");
		return false;
	}
	}
	if (StrDay.length!=0){
	if (StrDay.length!=2){
		window.alert("日期格式应为yyyy-mm-dd!")
		return false;
	}
	v = parseNum(StrDay);
	if(isNaN(v)){
		window.alert("日期必须为数字！");
		return false;
	}
	if(v < 1 || v > 31){
		window.alert("日期必须在1-31之间！");
		return false;
	}
	}
	return true;
}

//检查select
function ChkSelect(srcSelect,str){
	if (srcSelect.selectedIndex<0 || srcSelect.options[srcSelect.selectedIndex].value==""){
			window.alert(str);
			srcSelect.focus();
			return false;

		}
	else
		return true;
}

//检查text
function ChkText(srcText,length,str){
	if (BASEtrim(srcText.value)=="" || srcText.length>length ){
		window.alert(str);
		srcText.focus();
		return false;
	}
	else
		return true;
}



function alertErr(frmVal,msg){
	if (frmVal=="")
	{
		alert(msg);
		return false;
	}
}


//全选
function selectedAll(obj){
	for (i=0;i<obj.length;i++){
		obj.options[i].selected=true;
	}
}
/*
function selectedItem(obj,items){
	var itemsArray=new Array();
	itemsArray=items.split(",");
	for (i=0;i<obj.length;i++)
	{
		for (j=0;j<itemsArray.length;j++)
		{
			if (itemsArray[j]===obj.options[i].value)
			{
				obj.options[i].selected=true;
			}
		}
	}
}
*/

//选中的items
function selectedItem(obj,items){

	var str1=new String();
	var str2=new String();

	var itemsArray=new Array();
	itemsArray=items.split(",");
	for (i=0;i<obj.length;i++)
	{
		for (j=0;j<itemsArray.length;j++)
		{

			if (itemsArray[j].length==obj.options[i].value.length)
			{
				str1=itemsArray[j];
				str2=obj.options[i].value;
				a=equStr(str1,str2);

				if (a)
				{
				obj.options[i].selected=true;
				}
				//alert(j);
			}
			//alert("ok");
		}
	}
}

function equStr(strA,strB){
	var n;
	n=strA.length;
	for (k=0;k<n;k++){
		if (strA.charCodeAt(k)!==strB.charCodeAt(k))
		{
			return false;
		}
	}
	return true;

}


//取得所选值
function getSelected(oSelect){
var i=0;
var iLength=0;
var strValue="";

iLength=oSelect.length;

if (iLength!=0)
	{
		for (i=0;i<iLength;i++)
		{
			if (oSelect.options[i].selected==true)
			{
				strValue=strValue + oSelect.options[i].value + ",";

			}

		}
		//alert(strValue);
		return strValue;
	}
}



function getAllOption(oSelect){
var i=0;
var iLength=0;
var strValue="";

iLength=oSelect.length;
if (iLength==0){
	return "";
}
if (iLength!=0)
	{
		for (i=0;i<iLength;i++)
		{
			if (oSelect.options[i].value!="")
			{
				strValue=strValue + oSelect.options[i].value + ",";
			}


		}
		//alert(strValue);
		return strValue;
	}
}



function chkType(){
	if (frm.atype[0].checked)
	{
		frm.mediatype.disabled=false;
	}
	if (frm.atype[1].checked)
	{
		frm.mediatype.disabled=true;
		unSelectedAll(frm.mediatype);
	}
}

function unSelectedAll(oSelect){
	var j;
	j=oSelect.length;
	for (i=0;i<=j ;i++ )
	{
		oSelect.options[i].selected=false;
	}
}

function GetSelectValue(srcSelect){
    var count,i,SelectValue,bfirst;

	       bfirst=true;
	       SelectValue="";
	       count=srcSelect.options.length;

	       for(i=0;i<count;i++)
	       {
	         if(srcSelect.options[i].selected==true && srcSelect.options[i].value!="")
	         {
	            if(bfirst)
	            {
	               SelectValue=srcSelect.options[i].value;
	               bfirst=false;
	            }
	            else
	            {
  	               SelectValue=SelectValue+","+srcSelect.options[i].value
	            }
	          }
				//alert(SelectValue);
		   }

	       return SelectValue;

}

function GetSelectValueAll(srcSelect){
    var count,i,SelectValue,bfirst;
	       bfirst=true;
	       SelectValue="";
	       count=srcSelect.options.length;
	       for(i=0;i<count;i++)
	       {
			  if (srcSelect.options[i].value!="")
			  {
				  if(bfirst )
					{
					   SelectValue=srcSelect.options[i].value;
					   bfirst=false;
					}
					else
					{
					   SelectValue=SelectValue+","+srcSelect.options[i].value
					}
			  }

	       }
	       return SelectValue;

}


function GetSelectValueForOne(srcSelect){
    var count,i,SelectValue,bfirst;

	       bfirst=true;
	       SelectValue="";
	       count=srcSelect.options.length;

	       for(i=0;i<count;i++)
	       {
	         if(srcSelect.options[i].selected==true)
	         {
				SelectValue=srcSelect.options[i].value;
				break;
	          }
				//alert(SelectValue);
		   }

	       return SelectValue;

}

function getYear(i){
	var strYear;
	strYear=i.substring(0,4);
	return strYear;
}

function getMonth(i){
	var strMonth;
	strMonth=i.substring(5,7);
	return strMonth;
}

function getDay(i){
	var strDay;
	strDay=i.substring(8,10);
	return strDay;
}

function test(){
	alert("test");
}


function js_openpage(url) {
var newwin
newwin=window.open(url,"","scrollbars=yes,width=650,height=500");

 return false;
}

//取得select里的日期
function getSelectDate(item1,item2,item3){
  var strYear="";
  var strMonth="";
  var strDay="";
  var strDate="";

  strYear=getSelected(item1);
  strMonth=getSelected(item2);
  strDay=getSelected(item3);

  if (strYear=="," || strMonth=="," || strDay==","){
    strDate="";
  }
  else{
    strDate=strYear.substring(0,4) + "-" + strMonth.substring(0,2) + "-" + strDay.substring(0,2);
  }
  return strDate;
}

//取得text里的日期
function getTextDate(item1,item2,item3){
  var strYear="";
  var strMonth="";
  var strDay="";
  var strDate="";

  strYear=BASEtrim(item1.value);
  strMonth=BASEtrim(item2.value);
  strDay=BASEtrim(item3.value);

  if (strYear=="" || strMonth=="" || strDay==""){
    strDate="";
  }
  else{
    strDate=strYear + "-" + strMonth + "-" + strDay;
  }
  return strDate;
}

//取得checkbox的值
function getCheckBoxVal(obj){
	if(obj==undefined)return false;
	var str,i;
	str="";
	if (obj.length==null && obj.checked==true)
	{
		return obj.value;

	}
	for(i=0;i<obj.length;i++){
		if (obj[i].checked==true){
			str=str + obj[i].value + ",";
			//alert(obj[i].value);
		}
	}
	if (str!=""){
		str=str.substring(0,str.length-1);
	}
	return str;
}

//取得checkbox的值,暂时使用
function getCheckBoxValTemp(obj){
	if(obj==undefined)return false;
	var str,i;
	str="";
	if (obj.length==null && obj.checked==true)
	{
		return obj.value;

	}
	for(i=0;i<obj.length;i++){
		if (obj[i].checked==true){
			str=str + obj[i].value.substring(0,13) + ",";
			//alert(obj[i].value);
		}
	}
	if (str!=""){
		str=str.substring(0,str.length-1);
	}
	return str;
}

//设置checkbox
function setCheckBoxVal(obj,items){
	if(obj==undefined)return false;
	var i,j;
	var itemsArray=new Array();
	itemsArray=items.split(",");

	for(i=0;i<obj.length;i++){
		for(j=0;j<itemsArray.length;j++){
			if (obj[i].value==itemsArray[j]){
				obj[i].checked=true;
			}
		}
	}
}

//取得int
function getInt(f){
	if (BASEisNotInt(f.value)){
		return parseInt(0);
	}
	else{
		return parseInt(f.value);
	}
}
//取得double
function getDouble(f){
	if (BASEisNotFloat(f.value)){
		return parseFloat(0);
	}
	else{
		return parseFloat(f.value);
	}
}

//取得利率，x为本金，v为利率0.05
function getRate(x,v){
	var y;
	if(isNaN(x)){
		return 0;
	}
	if(isNaN(v)){
		return 0;
	}
	y=x*v;
	y=fun45(y);
	return y;
}

//四舍五入
function fun45(v){
	var x,y,y1,y2,z;
	if (isNaN(v)){
		return parseFloat(0);
	}
	else{
		v=parseFloat(v);
	}
	x=v*1000;
	x=parseInt(x);
	x=x/10;
	y=new String(x);
	if (y.indexOf(".")==-1){
		//alert(x/100);
		return x/100;
	}
	else{
		//alert(y);
		z=y.indexOf(".");
		y1=y.substring(0,z);
		y1=parseInt(y1);
		y2=y.substring(z+1,y.length);
		y2=parseInt(y2);
		if (y2>=5){y1++;}
		return y1/100;
	}
}

function isRadioChecked(obj){
	if(obj==undefined)return false;;
	if (obj.length==null){
		if (obj.checked){
			return true;
		}
		else{
			return false;
		}
	}
	for(i=0;i<obj.length;i++){
		if (obj[i].checked){
			return true;
		}
	}
	return false;
}

function getRadioValue(obj){
	if(obj==undefined)return "";
	if (obj.length==null || obj.length==1){
		if (obj.checked){
			return obj.value;
		}
		else{
			return "";
		}
	}
	else{
		for(i=0;i<obj.length;i++){
			if (obj[i].checked){
				return obj[i].value;
			}
		}
		return "";
	}
}

function getLeftStr(str,tag){
	var i=str.indexOf(tag);
	//alert(i);
	var tmpstr=str.substring(0,i);
	return tmpstr;
}

function getRightStr(str,tag){
	var i=str.indexOf(tag);
	//alert(i);
	var tmpstr=str.substring(i+1,str.length);
	return tmpstr;
}

function moveSelectOptions(select1,select2)
{
  var count,i,oOption;
  count=select1.options.length;
  //alert(count);
  /*
  for(i=0;i<select2.options.length;i++)
  {
    if(select2.options[i].value=="") {
		select2.options[i] = null;
		i = i - 1;
	}
  }
  */
  for(i=0;i<count;i++)
  {
    //alert(i);
    if(select1.options[i].selected==true && select1.options[i].value!="")
    {
      oOption = new Option;
      oOption.text=select1.options[i].text;
      oOption.value=select1.options[i].value;
      //select2.add(oOption);
      select2.options[select2.length]=oOption;
//      select1.remove(i);
    }
  }
  //alert("del");
  for(i=0;i<select1.options.length;i++)
  {
    //alert(select1.options.length);
    //alert(i);
    if(select1.options[i].selected==true && select1.options[i].value!="")
    {
      select1.options[i] = null;
      i = i - 1;
    }
  }
  if(select1.options.length==0)
  {
  	oOption=new Option;
	oOption.text="----------";
	oOption.value="";
	select1.options[0]=oOption;
  }
}

//从string中分离出字符数组来
function split(str,v){
	var q=0;
	for(var i=0;i<str.length;i++){
		if (str.charAt(i)==v){
			q++;
		}
	}

	var ary=new Array(q);
	if (q==0){
		return ary;
	}
	else{
       var x=0;
       var y=-1;
	   var z=0;
       while(x!=-1){
         var tmp="";
         x=str.indexOf(",",x+1);
         if (x!=-1){
           tmp = str.substring(y + 1, x);
           y=x;
           if (tmp!=""){
			   ary[z]=tmp;
			   z++;
		   }
		   //alert(tmp);
         }
         if (x==-1 && y<str.length){
           tmp=str.substring(y+1,str.length);
		   if (tmp!=""){
			ary[z]=tmp;
		   }
         }

       }
	   return ary;
	}
}

//将字符数组生成字符串
function arrayTostr(ary,v){
	var str="";
	for(var i=0;i<ary.length;i++){
		if (ary[i]!=""){
			str=str + ary[i] + v;
		}
	}
	return str;
}

function openNew(url,title){
window.open(url, title, "toolbar,menubar,scrollbars,resizable,status,location,directories,copyhistory,height=600,width=800")
}

function initArray(){for(i=0;i<initArray.arguments.length;i++)
this[i]=initArray.arguments[i];}
var isnMonths=new initArray("1月","2月","3月","4月","5月","6月","7月","8月","9月","10月","11月","12月");var isnDays=new initArray("星期日","星期一","星期二","星期三","星期四","星期五","星期六","星期日");
today=new Date();hrs=today.getHours();min=today.getMinutes();
sec=today.getSeconds();
clckh=""+((hrs>12)?hrs-12:hrs);
clckm=((min<10)?"0":"")+min;
clcks=((sec<10)?"0":"")+sec;
if(hrs>=12&&hrs<=18){
	clck="下午好";
}else if(hrs>=6&&hrs<=12){
    clck="上午好";
}else{
clck="晚上好";
}
//clck=(hrs>=12)?"下午好":"上午好";
var stnr="";
var ns="0123456789";
var a="";
//-->
/*************鼠标放在list上出现行颜色变化效果函数**************/
function moveOut(src){
 	src.className="list-double";
}

function moveOver(src){
    var rows=src.parentElement.rows.length;
 	for(i=1;i<rows;i++){
		src.parentElement.rows(i-1).className="list-double";
	}
 	src.className="list-single";
}
/*******************鼠标放在list上出现行颜色变化效果函数结束***************/
/*******list全选函数***********/
function changecheck(objHead,objcontext){
	if(objHead.checked==true){
		if(objcontext==undefined || objcontext.disabled==true){
			return ;
		}
		else if (objcontext.length==undefined && objcontext.disabled==false){
			objcontext.checked=true;
		}else{


			for(var i=0;i<objcontext.length;i++){
				if (objcontext[i].disabled==false)
				{

				objcontext[i].checked=true;
				}
			}
		}
	}else {
		if(objcontext==undefined || objcontext.disabled==true){
			return ;
		}else if (objcontext.length==undefined && objcontext.disabled==false){
			objcontext.checked=false;
		}else{
			for(var i=0;i<objcontext.length;i++){
				if(objcontext[i].disabled==false){
				objcontext[i].checked=false;
				}
			}
		}
   }
}
/*******list全选函数结束***********/


//false:身份证号码不对
function check_ID_card(ID_card) {//身份证校验
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
  return bReturn;

}

 //检查一段字符串是否全由数字组成
function checkNum(str){return str.match(/\D/)==null}

//出去两边空格
String.prototype.trim = function()
{
  return this.replace(/(^\s*)|(\s*$)/g,"");
}

//*****************************************时间JS代码开始*************************//

//==================================================== 参数设定部分 =======================================================
var bMoveable=false;                //设置日历是否可以拖动
var _VersionInfo="Version:2.0 2.0作者:walkingpoison 1.0作者: F.R.Huang(meizz) MAIL: meizz@hzcnc.com"        //版本信息

//==================================================== WEB 页面显示部分 =====================================================
var strFrame;                //存放日历层的HTML代码
document.writeln('<iframe name=meizzDateLayer Author=wayx frameborder=0 style="position: absolute; width: 156; height: 202; z-index: 9998; visibility:hidden"></iframe>');
strFrame='<style>';
strFrame+='INPUT.button{width:22px;background-image:url(../../images/button_min.gif); border:none;';
strFrame+='cursor:hand;height:20px;color:#1A407D;}';
strFrame+='INPUT.button2{width:36px;background-image:url(../../images/button5.jpg); border:none;';
strFrame+='cursor:hand;height:20px;color:#1A407D;}';
strFrame+='TD{FONT-SIZE: 9pt;font-family:宋体;}';
strFrame+='A{text-decoration:none;font-weight:none;color:black}';
strFrame+='</style>';
strFrame+='<scr' + 'ipt>';
strFrame+='var datelayerx,datelayery;        /*存放日历控件的鼠标位置*/';
strFrame+='var bDrag;        /*标记是否开始拖动*/';
strFrame+='function document_onmousemove(e){/*在鼠标移动事件中，如果开始拖动日历，则移动日历*/';
strFrame+='if(bDrag){';
strFrame+='        var DateLayer=parent.document.getElementsByName("meizzDateLayer")[0].style;';
strFrame+='                DateLayer.left = parseInt(DateLayer.left)+ e.clientX-datelayerx; /*由于每次移动以后鼠标位置都恢复为初始的位置，因此写法与div中不同*/';
strFrame+='if(DateLayer.top=="")DateLayer.top=0;';
strFrame+='                DateLayer.top = parseInt(DateLayer.top)+ e.clientY-datelayery;}}';
strFrame+='function DragStart(e){                /*开始日历拖动*/';
strFrame+="if ((document.all?e.button:e.which)!=1) return;\n";
strFrame+='var DateLayer=parent.document.getElementsByName("meizzDateLayer")[0].style;';
strFrame+='        datelayerx=e.clientX;';
strFrame+='        datelayery=e.clientY;';
strFrame+='        bDrag=true;}';
strFrame+='function DragEnd(){                /*结束日历拖动*/';
strFrame+='        bDrag=false;}';
strFrame+='</scr' + 'ipt>';
strFrame+='<body onmousemove="document_onmousemove(event)">';
strFrame+='<div style="z-index:9999;position: absolute; left:0; top:0;" onselectstart="return false"><span id=tmpSelectYearLayer Author=wayx style="z-index: 9999;position: absolute;top: 3; left: 19;visibility: hidden" align=center></span>';
strFrame+='<span id=tmpSelectMonthLayer Author=wayx style="z-index: 9999;position: absolute;top: 3; left: 78;visibility: hidden"></span>';
strFrame+='<table border=1 cellspacing=0 cellpadding=0 width=100% height=160 bordercolordark=#FFFFFF  bgcolor=#FFFFFF Author="wayx">';
strFrame+='  <tr Author="wayx"><td width=100% height=23 Author="wayx" bgcolor=#FFFFFF><table border=0 cellspacing=1 cellpadding=0 width=99% Author="wayx" height=23>';
strFrame+='  <tr align=center Author="wayx"><td width=16 align=center bgcolor=#cccccc';
strFrame+=' onclick="parent.meizzPrevM()" title="向前翻 1 月" Author=meizz><a href="javascript:;" style="font-size:12px;color: #ffffff" Author=meizz><</a>';
strFrame+='        </td><td width=60 align=center style="font-size:12px;cursor:default" Author=meizz ';
strFrame+='onmouseover="style.backgroundColor=\'#FFD700\'" onmouseout="style.backgroundColor=\'white\'" ';
strFrame+='onclick="parent.tmpSelectYearInnerHTML(innerHTML.match(/\\d{4}/).toString());" title="点击这里选择年份"><span Author=meizz id=meizzYearHead></span></td>';
strFrame+='<td width=48 align=center style="font-size:12px;cursor:default" Author=meizz onmouseover="style.backgroundColor=\'#ECECEC\'" ';
strFrame+=' onmouseout="style.backgroundColor=\'white\'" onclick="parent.tmpSelectMonthInnerHTML(innerHTML.match(/\\d\\d?/).toString())"';
strFrame+='        title="点击这里选择月份"><span id=meizzMonthHead Author=meizz></span></td>';
strFrame+='        <td width=16 bgcolor=#cccccc align=center style="font-size:12px;cursor: hand;color: #ffffff" ';
strFrame+=' onclick="parent.meizzNextM()" title="向后翻 1 月" Author=meizz><a href="javascript:;" style="font-size:12px;color: #ffffff;" Author=meizz>></a></td></tr>';
strFrame+='    </table></td></tr>';
strFrame+='  <tr Author="wayx"><td width=100% height=18 Author="wayx">';
strFrame+='<table border=1 cellspacing=0 cellpadding=0 bgcolor=#FFFFFF ' + (bMoveable? 'onmousedown="DragStart(event)" onmouseup="DragEnd()"':'');
strFrame+=' BORDERCOLORLIGHT=#FFFFFF BORDERCOLORDARK=#FFFFFF width=99% height=14 Author="wayx" style="cursor:' + (bMoveable ? 'move':'default') + '">';
strFrame+='<tr Author="wayx" align=center valign=bottom><td style="font-size:12px;color:#003399" Author=meizz>日</td>';
strFrame+='<td style="font-size:12px;color:#003399" Author=meizz>一</td><td style="font-size:12px;color:#003399" Author=meizz>二</td>';
strFrame+='<td style="font-size:12px;color:#003399" Author=meizz>三</td><td style="font-size:12px;color:#003399" Author=meizz>四</td>';
strFrame+='<td style="font-size:12px;color:#003399" Author=meizz>五</td><td style="font-size:12px;color:#003399" Author=meizz>六</td></tr>';
strFrame+='</table></td></tr>';
strFrame+='  <tr Author="wayx"><td width=100% height=120 Author="wayx">';
strFrame+='    <table border=1 cellspacing=1 cellpadding=0 BORDERCOLORLIGHT=#FFFFFF BORDERCOLORDARK=#FFFFFF bgcolor=#FFFFFF width=99% height=120 Author="wayx">';
var n=0; for (j=0;j<5;j++){ strFrame+= ' <tr align=center Author="wayx">'; for (i=0;i<7;i++){
strFrame+='<td width=20 height=20 id=meizzDay'+n+' style="font-size:12px"  onclick=parent.meizzDayClick(this.innerHTML.match(/\\d+/).toString(),0)></td>';n++;}
strFrame+='</tr>';}
strFrame+='      <tr align=center Author="wayx">';
for (i=35;i<39;i++)strFrame+='<td width=20 height=20 id=meizzDay'+i+' onclick="parent.meizzDayClick(this.innerHTML.match(/\\d+/).toString(),0)"></td>';
strFrame+='        <td colspan=3 align=right Author=meizz><a href="javascript:;" onclick=parent.closeLayer() style="font-size:12px;text-decoration:none;font-weight:300;"';
strFrame+='         Author=meizz title="' + _VersionInfo + '">关闭</a> </td></tr>';
strFrame+='    </table></td></tr><tr Author="wayx"><td Author="wayx">';
strFrame+='        <table border=0 cellspacing=1 cellpadding=0 width=100% Author="wayx" bgcolor=#FFFFFF>';
strFrame+='          <tr Author="wayx"><td Author=meizz align=left><input Author=meizz type=button class=button value="<<" title="向前翻 1 年" onclick="parent.meizzPrevY()" ';
strFrame+='             onfocus="this.blur()" style="font-size: 12px; height: 20px">&nbsp;<input Author=meizz class=button title="向前翻 1 月" type=button ';
strFrame+='             value="< " onclick="parent.meizzPrevM()" onfocus="this.blur()" style="font-size: 12px; height: 20px"></td><td ';
strFrame+='             Author=meizz align=center>&nbsp;<input Author=meizz type=button class=button2 value=今日 onclick="parent.meizzToday()" ';
strFrame+='             onfocus="this.blur()" title="当前日期" style="font-size: 12px; height: 20px; cursor:hand">&nbsp;</td><td ';
strFrame+='             Author=meizz align=right><input Author=meizz type=button class=button value=" >" onclick="parent.meizzNextM()" ';
strFrame+='             onfocus="this.blur()" title="向后翻 1 月" class=button style="font-size: 12px; ">&nbsp;<input ';
strFrame+='             Author=meizz type=button class=button value=">>" title="向后翻 1 年" onclick="parent.meizzNextY()"';
strFrame+='             onfocus="this.blur()" style="font-size: 12px; height: 20px"></td>';
strFrame+='</tr></table></td></tr></table></div></body>';
var odatelayer;
var outObject;
var outButton;                //点击的按钮
var outDate="";                //存放对象的日期
function init(){
        window.frames.meizzDateLayer.document.writeln(strFrame);
        window.frames.meizzDateLayer.document.close();                //解决ie进度条不结束的问题
        odatelayer=window.frames.meizzDateLayer.document;                //存放日历对象
}
//==================================================== WEB 页面显示部分 ======================================================

function setday(tt,obj) //主调函数
{
        if (arguments.length >  2){alert("对不起！传入本控件的参数太多！");return;}
        if (arguments.length == 0){alert("对不起！您没有传回本控件任何参数！");return;}
        var dads  = document.getElementsByName("meizzDateLayer")[0].style;
        var th = tt;
        var ttop  = tt.offsetTop;     //TT控件的定位点高
        var thei  = tt.offsetHeight;  //TT控件本身的高
        var tleft = tt.offsetLeft;    //TT控件的定位点宽
        var ttyp  = tt.type;          //TT控件的类型
        while (tt = tt.offsetParent){ttop+=tt.offsetTop; tleft+=tt.offsetLeft;}
        dads.top  = ttop+thei ;
        dads.left = tleft;
        outObject = (arguments.length == 1) ? th : obj;
        outButton = (arguments.length == 1) ? null : th;        //设定外部点击的按钮
        //根据当前输入框的日期显示日历的年月
        var reg = /^(\d+)-(\d{1,2})-(\d{1,2})$/;
        var r = outObject.value.match(reg);
        if(r!=null){
                r[2]=r[2]-1;
                var d= new Date(r[1], r[2],r[3]);
                if(d.getFullYear()==r[1] && d.getMonth()==r[2] && d.getDate()==r[3]){
                        outDate=d;                //保存外部传入的日期
                }
                else outDate="";
                        meizzSetDay(r[1],r[2]+1);
        }
        else{
                outDate="";
                meizzSetDay(new Date().getFullYear(), new Date().getMonth() + 1);
        }
        dads.visibility="visible";
        document.getElementsByName("meizzDateLayer")[0].focus();
        event.returnValue=false;
}

var MonHead = new Array(12);                       //定义阳历中每个月的最大天数
    MonHead[0] = 31; MonHead[1] = 28; MonHead[2] = 31; MonHead[3] = 30; MonHead[4]  = 31; MonHead[5]  = 30;
    MonHead[6] = 31; MonHead[7] = 31; MonHead[8] = 30; MonHead[9] = 31; MonHead[10] = 30; MonHead[11] = 31;

var meizzTheYear=new Date().getFullYear(); //定义年的变量的初始值
var meizzTheMonth=new Date().getMonth()+1; //定义月的变量的初始值
var meizzWDay=new Array(39);               //定义写日期的数组

function document_onclick(e) //任意点击时关闭该控件        //ie6的情况可以由下面的切换焦点处理代替
{
        var elm = (document.all)?(e.srcElement):(e.target);
        if ((elm.getAttribute("Author")==null || elm.getAttribute("Author")=="") && elm != outObject && elm != outButton)
    closeLayer();
}

function document_onkeyup(e)                //按Esc键关闭，切换焦点关闭
  {
    if (window.event.keyCode==27){
                if(outObject)outObject.blur();
                closeLayer();
        }
        else if(document.activeElement)
                if(document.activeElement.getAttribute("Author")==null && document.activeElement != outObject && document.activeElement != outButton)
                {
                        closeLayer();
                }
  }

function meizzWriteHead(yy,mm)  //往 head 中写入当前的年与月
  {
        odatelayer.getElementById("meizzYearHead").innerHTML  = yy + " 年";
    odatelayer.getElementById("meizzMonthHead").innerHTML = mm + " 月";
  }

function tmpSelectYearInnerHTML(strYear) //年份的下拉框
{
  if (strYear.match(/\D/)!=null){alert("年份输入参数不是数字！");return;}
  var m = (strYear) ? strYear : new Date().getFullYear();
  if (m < 1000 || m > 9999) {alert("年份值不在 1000 到 9999 之间！");return;}
  var n = m - 10;
  if (n < 1000) n = 1000;
  if (n + 26 > 9999) n = 9974;
  var s = "<select Author=meizz name=tmpSelectYear style='font-size: 12px' "
     s += "onblur='document.getElementById(\"tmpSelectYearLayer\").style.visibility=\"hidden\"' "
     s += "onchange='blur();"
     s += "parent.meizzTheYear = this.value; parent.meizzSetDay(parent.meizzTheYear,parent.meizzTheMonth)'>\r\n";
  var selectInnerHTML = s;
  for (var i = n; i < n + 26; i++)
  {
    if (i == m)
       {selectInnerHTML += "<option Author=wayx value='" + i + "' selected>" + i + "年" + "</option>\r\n";}
    else {selectInnerHTML += "<option Author=wayx value='" + i + "'>" + i + "年" + "</option>\r\n";}
  }
  selectInnerHTML += "</select>";
  odatelayer.getElementById("tmpSelectYearLayer").innerHTML = selectInnerHTML;
  odatelayer.getElementById("tmpSelectYearLayer").style.visibility="visible";
  odatelayer.getElementsByName("tmpSelectYear")[0].focus();

}

function tmpSelectMonthInnerHTML(strMonth) //月份的下拉框
{
  if (strMonth.match(/\D/)!=null){alert("月份输入参数不是数字！");return;}
  var m = (strMonth) ? strMonth : new Date().getMonth() + 1;
  var s = "<select Author=meizz name=tmpSelectMonth style='font-size: 12px' "
     s += "onblur='document.getElementById(\"tmpSelectMonthLayer\").style.visibility=\"hidden\"' "
     s += "onchange='blur();"
     s += "parent.meizzTheMonth = this.value; parent.meizzSetDay(parent.meizzTheYear,parent.meizzTheMonth)'>\r\n";
  var selectInnerHTML = s;
  for (var i = 1; i < 13; i++)
  {
    if (i == m)
       {selectInnerHTML += "<option Author=wayx value='"+i+"' selected>"+i+"月"+"</option>\r\n";}
    else {selectInnerHTML += "<option Author=wayx value='"+i+"'>"+i+"月"+"</option>\r\n";}
  }
  selectInnerHTML += "</select>";
  odatelayer.getElementById("tmpSelectMonthLayer").style.visibility="visible";
  odatelayer.getElementById("tmpSelectMonthLayer").innerHTML = selectInnerHTML;
  odatelayer.getElementsByName("tmpSelectMonth")[0].focus();
}

function closeLayer()               //这个层的关闭
  {
    document.getElementsByName("meizzDateLayer")[0].style.visibility="hidden";
//    checkTime();
  }

function IsPinYear(year)            //判断是否闰平年
  {
    if (0==year%4&&((year%100!=0)||(year%400==0))) return true;else return false;
  }

function GetMonthCount(year,month)  //闰年二月为29天
  {
    var c=MonHead[month-1];if((month==2)&&IsPinYear(year)) c++;return c;
  }
function GetDOW(day,month,year)     //求某天的星期几
  {
    var dt=new Date(year,month-1,day).getDay()/7; return dt;
  }

function meizzPrevY()  //往前翻 Year
  {
    if(meizzTheYear > 999 && meizzTheYear <10000){meizzTheYear--;}
    else{alert("年份超出范围（1000-9999）！");}
    meizzSetDay(meizzTheYear,meizzTheMonth);
  }
function meizzNextY()  //往后翻 Year
  {
    if(meizzTheYear > 999 && meizzTheYear <10000){meizzTheYear++;}
    else{alert("年份超出范围（1000-9999）！");}
    meizzSetDay(meizzTheYear,meizzTheMonth);
  }
function meizzToday()  //Today Button
  {
        var today;
    meizzTheYear = new Date().getFullYear();
    meizzTheMonth = new Date().getMonth()+1;
    today=new Date().getDate();
    //meizzSetDay(meizzTheYear,meizzTheMonth);
    if(outObject){
		if(meizzTheMonth<'10'){
			meizzTheMonth="0"+meizzTheMonth
		}
		if(today<'10'){
			today="0"+today
		}
                outObject.value=meizzTheYear + "-" + meizzTheMonth + "-" + today;
    }
    closeLayer();
  }
function meizzPrevM()  //往前翻月份
  {
    if(meizzTheMonth>1){meizzTheMonth--}else{meizzTheYear--;meizzTheMonth=12;}
    meizzSetDay(meizzTheYear,meizzTheMonth);
  }
function meizzNextM()  //往后翻月份
  {
    if(meizzTheMonth==12){meizzTheYear++;meizzTheMonth=1}else{meizzTheMonth++}
    meizzSetDay(meizzTheYear,meizzTheMonth);
  }

function meizzSetDay(yy,mm)   //主要的写程序**********
{
  meizzWriteHead(yy,mm);
  //设置当前年月的公共变量为传入值
  meizzTheYear=yy;
  meizzTheMonth=mm;
  for (var i = 0; i < 39; i++){meizzWDay[i]=""};  //将显示框的内容全部清空
  var day1 = 1,day2=1,firstday = new Date(yy,mm-1,1).getDay();  //某月第一天的星期几
  for (i=0;i<firstday;i++)meizzWDay[i]=GetMonthCount(mm==1?yy-1:yy,mm==1?12:mm-1)-firstday+i+1        //上个月的最后几天
  for (i = firstday; day1 < GetMonthCount(yy,mm)+1; i++){meizzWDay[i]=day1;day1++;}
  for (i=firstday+GetMonthCount(yy,mm);i<39;i++){meizzWDay[i]=day2;day2++}
  for (i = 0; i < 39; i++)
  { var da = odatelayer.getElementById("meizzDay"+i);     //书写新的一个月的日期星期排列
    if (meizzWDay[i]!="")
      {
                //初始化边框
                da.style.borderWidth="0px";
                da.style.borderStyle="none";
                da.style.borderTopColor="#FFFFFF";
                da.style.borderRightColor="#FFFFFF";
                da.style.borderBottomColor="#FFFFFF";
                da.style.borderLeftColor="#FFFFFF";
                if(i<firstday)                //上个月的部分
                {
                        da.innerHTML="<a href='javascript:;'><font color=#aaaaaa>" + meizzWDay[i] + "</font></a>";
                        da.title=(mm==1?12:mm-1) +"月" + meizzWDay[i] + "日";
                        da.onclick=Function("meizzDayClick(this.innerHTML.match(/\\d+/).toString(),-1)");
                        if(!outDate)
                                da.style.backgroundColor = ((mm==1?yy-1:yy) == new Date().getFullYear() &&
                                        (mm==1?12:mm-1) == new Date().getMonth()+1 && meizzWDay[i] == new Date().getDate()) ?
                                         "#FFD700":"#ececec";
                        else
                        {
                                da.style.backgroundColor =((mm==1?yy-1:yy)==outDate.getFullYear() && (mm==1?12:mm-1)== outDate.getMonth() + 1 &&
                                meizzWDay[i]==outDate.getDate())? "#003399" :
                                (((mm==1?yy-1:yy) == new Date().getFullYear() && (mm==1?12:mm-1) == new Date().getMonth()+1 &&
                                meizzWDay[i] == new Date().getDate()) ? "#ececec":"#e0e0e0");
                                //将选中的日期显示为凹下去
                                if((mm==1?yy-1:yy)==outDate.getFullYear() && (mm==1?12:mm-1)== outDate.getMonth() + 1 &&
                                meizzWDay[i]==outDate.getDate())
                                {
                                        da.style.borderTopColor="#FFFFFF";
                                        da.style.borderRightColor="#ececec";
                                        da.style.borderBottomColor="#ececec";
                                        da.style.borderLeftColor="#FFFFFF";
                                }
                        }
                }
                else if (i>=firstday+GetMonthCount(yy,mm))                //下个月的部分
                {
                        da.innerHTML="<a href='javascript:;'><font color=#aaaaaa>" + meizzWDay[i] + "</font></a>";
                        da.title=(mm%12+1) +"月" + meizzWDay[i] + "日";
                        da.onclick=Function("meizzDayClick(this.innerHTML.match(/\\d+/).toString(),1)");
                        if(!outDate)
                                da.style.backgroundColor = ((mm==12?yy+1:yy) == new Date().getFullYear() &&
                                        (mm==12?1:mm+1) == new Date().getMonth()+1 && meizzWDay[i] == new Date().getDate()) ?
                                         "#FFD700":"#ececec";
                        else
                        {
                                da.style.backgroundColor =((mm==12?yy+1:yy)==outDate.getFullYear() && (mm==12?1:mm+1)== outDate.getMonth() + 1 &&
                                meizzWDay[i]==outDate.getDate())? "#ececec" :
                                (((mm==12?yy+1:yy) == new Date().getFullYear() && (mm==12?1:mm+1) == new Date().getMonth()+1 &&
                                meizzWDay[i] == new Date().getDate()) ? "#ececec":"#e0e0e0");
                                //将选中的日期显示为凹下去
                                if((mm==12?yy+1:yy)==outDate.getFullYear() && (mm==12?1:mm+1)== outDate.getMonth() + 1 &&
                                meizzWDay[i]==outDate.getDate())
                                {
                                        da.style.borderTopColor="#FFFFFF";
                                        da.style.borderRightColor="#ececec";
                                        da.style.borderBottomColor="#ececec";
                                        da.style.borderLeftColor="#FFFFFF";
                                }
                        }
                }
                else                //本月的部分
                {
                        da.innerHTML="<a href='javascript:;'>" + meizzWDay[i] + "</a>";
                        da.title=mm +"月" + meizzWDay[i] + "日";
                        da.onclick=Function("meizzDayClick(this.innerHTML.match(/\\d+/).toString(),0)");                //给td赋予onclick事件的处理
                        //如果是当前选择的日期，则显示亮蓝色的背景；如果是当前日期，则显示暗黄色背景
                        if(!outDate)
                                da.style.backgroundColor = (yy == new Date().getFullYear() && mm == new Date().getMonth()+1 && meizzWDay[i] == new Date().getDate())?
                                        "#ffcc00":"#cccccc";
                        else
                        {
                                da.style.backgroundColor =(yy==outDate.getFullYear() && mm== outDate.getMonth() + 1 && meizzWDay[i]==outDate.getDate())?
                                        "#00ffff":((yy == new Date().getFullYear() && mm == new Date().getMonth()+1 && meizzWDay[i] == new Date().getDate())?
                                        "#ffcc00":"#cccccc");
                                //将选中的日期显示为凹下去
                                if(yy==outDate.getFullYear() && mm== outDate.getMonth() + 1 && meizzWDay[i]==outDate.getDate())
                                {
                                        da.style.borderTopColor="#FFFFFF";
                                        da.style.borderRightColor="#ececec";
                                        da.style.borderBottomColor="#ececec";
                                        da.style.borderLeftColor="#FFFFFF";
                                }
                        }
                }
        da.style.cursor="hand"
      }
    else{da.innerHTML="";da.style.backgroundColor="";da.style.cursor="default"}
  }
}
   //格式化Double型字符串
   function FormatDoubleStr(doubleVal,len){
      var doubleStr=""+doubleVal;
      var temStr="";
      if(doubleStr!=null && BASEtrim(doubleStr)!="" && BASEtrim(doubleStr).toUpperCase()!="NULL"){
          var p=doubleStr.lastIndexOf('.');
          if(p<0){
             temStr=doubleStr+".";
             for(var i=0;i<len;i++){
                temStr=temStr+"0";
             }
          }else{
             var q=doubleStr.substring(p + 1).length;
             if(p==0){
                temStr="0";
             }
             if(q>len){
                temStr=temStr+doubleStr.substring(0,(p+1)+len);
             }else{
                temStr=temStr+doubleStr;
                for(var i=0;i<(len-q);i++){
                   temStr=temStr+"0";
                }
             }
          }
      }else{
          temStr="0.";
          for(var i=0;i<len;i++){
             temStr=temStr+"0";
          }
      }
      return temStr;
   }
function meizzDayClick(n,ex)  //点击显示框选取日期，主输入函数*************
{
  var yy=meizzTheYear;
  var mm = parseInt(meizzTheMonth)+ex;        //ex表示偏移量，用于选择上个月份和下个月份的日期
        //判断月份，并进行对应的处理
        if(mm<1){
                yy--;
                mm=12+mm;
        }
        else if(mm>12){
                yy++;
                mm=mm-12;
        }

  if (mm < 10){mm = "0" + mm;}
  if (outObject)
  {
    if (!n) {//outObject.value="";
      return;}
    if ( n < 10){n = "0" + n;}
    outObject.value= yy + "-" + mm + "-" + n ; //注：在这里你可以输出改成你想要的格式
    closeLayer();
  }
  else {closeLayer(); alert("您所要输出的控件对象并不存在！");}
}
//**************************************************时间代码结束*****************************************//

/*获取帮助的热键F1函数***********************/
//function window.onhelp(){return false;}
/*
function document.onkeydown(){
   if (event.keyCode=='112'){

var tmp=window.open("about:blank","","toolbar=no,resizable,scrollbars=yes,dependent=0,status=1, left=120,top=80,width=850,height=600")


tmp.focus()

tmp.location="/sms/syshelp/index.jsp"
   }
}*/
function getRadioValue(obj){
	if(obj==undefined)return "";
	if (obj.length==null || obj.length==1){
		if (obj.checked){
			return obj.value;
		}
		else{
			return "";
		}
	}
	else{
		for(i=0;i<obj.length;i++){
			if (obj[i].checked){
				return obj[i].value;
			}
		}
		return "";
	}
}

function getCheckBoxVal(obj){
	if(obj==undefined)return false;
	var str,i;
	str="";
	if (obj.length==null && obj.checked==true)
	{
		return obj.value;

	}
	for(i=0;i<obj.length;i++){
		if (obj[i].checked==true){
			str=str + obj[i].value + ",";
			//alert(obj[i].value);
		}
	}
	if (str!=""){
		str=str.substring(0,str.length-1);
	}
	return str;
}
function changecheck(objHead,objcontext){
	if(objHead.checked==true){
		if(objcontext==undefined || objcontext.disabled==true){
			return ;
		}
		else if (objcontext.length==undefined && objcontext.disabled==false){
			objcontext.checked=true;
		}else{


			for(var i=0;i<objcontext.length;i++){
				if (objcontext[i].disabled==false)
				{

				objcontext[i].checked=true;
				}
			}
		}
	}else {
		if(objcontext==undefined || objcontext.disabled==true){
			return ;
		}else if (objcontext.length==undefined && objcontext.disabled==false){
			objcontext.checked=false;
		}else{
			for(var i=0;i<objcontext.length;i++){
				if(objcontext[i].disabled==false){
				objcontext[i].checked=false;
				}
			}
		}
   }
}
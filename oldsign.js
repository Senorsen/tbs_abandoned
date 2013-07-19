
var nofail=true;
var globalfail=false;

function signone(uid,tb,jid,tid)
{
	//签某个吧
	var url="sign.php?id="+uid+"&tb="+escape(tb);
	var rv;
	$.getJSON(url,"",function(data){
		rv=data;
	});
	var retobj,failtype=2,retstr;
	if(typeof(rv)=="undefined")
	{
		//未知错误
		retstr="未知错误(重试中...)";
		retobj = {"errnum":1,"rs":"未知错误(重试中...)"};
	}
	else
	{
		failtype=rv["status"];
		if(rv["status"]=="0")
		{
			//成功
			retstr=rv["returnval"];
			retobj = {"errnum":0,"rs":retstr};
		}
		else
		{
			if(rv["status"]=="1")
			{
				//失败：此吧无签到功能（但是不用重新签到）
				retstr=rv["returnval"];
				retobj = {"errnum":0,"rs":retstr};
			}
			else
			{
				if(rv["status"]=="2")
				{
					//某已知错误
					retstr=rv["returnval"];
					retobj = {"errnum":1,"rs":retstr};
				}
			}
		}
	}
	$("#statu"+jid+"t"+tid).attr("class","st"+failtype);
	$("#statu"+jid+"t"+tid).html(failtype==2&&(!/封/.test(retstr))?retstr+"(重试中...)":retstr);
	if((failtype!=2||/封/.test(retstr))&&tid>3)
	{
		//document.getElementsByTagName("table")[jid].getElementsByTagName("tr")[tid-3].innerHTML="";
	}
	return retobj;
}

function setstat(uid,t)
{
	$.getJSON("setok.php?t="+t+"&id="+uid,"",function(data){
		if(data["status"]=="0")setok(uid,t);		//失败的重试
	});
}

function setno()
{
	for(var i=0;i<id.length;i++)
	{
		setstat(id[i],"disable");
		issigned[i]=false;
	}
	alert("去使能 "+id.length+" 个用户");
}

function sign(uid,jid)
{
	if(dep==like[jid].length)
	{
		//document.getElementsByTagName("table")[jid].getElementsByTagName("tr")[0].className="hs";
		if(nofail&&dep!=0)
		{
			$("table")[jid].innerHTML="<tr class=hs><td>"+desc[jid]+":签到完成</td></tr>";
		}
		else
		{
			$("table")[jid].innerHTML="<tr class=hf><td>"+desc[jid]+":某些原因签到失败</td></tr>";
			globalfail=true;
		}
		//$("#statu"+jid).html("状态:完成");
		if(!issigned[jid]) 
		{
			if(nofail&&dep!=0)setstat(uid,"enable");		//记录：该用户已完成今天的签到
			clearInterval(intervalid);
		}
		finishnum++;
		if(finishnum==id.length)
		{
			if(globalfail)document.body.bgColor="#ee0000";else document.body.bgColor="#00ee00";
			$("#allstat").html("<font color=#ffffff><b>签到全部完成！</b></font>");
			//scroll(0,0);
		}
		else
		{
			scroll(0,document.getElementById("userjid"+(jid)).offsetTop);
			allsign();
		}
		return;
	}
	var retobj=signone(uid,like[jid][dep],jid,dep);
	if(retobj["errnum"]==0)
	{
		dep++;
	}
/*
	if(/封/.test(retobj["rs"]))
	{
		dep++;nofail=false;
	}
*/
}

function allsign()
{
	//全部签到
	thisuser++;
	if(thisuser==id.length) return;
	$("#allstat").html("正在签到……");
	if(issigned[thisuser])
	{
		nofail=true;
		dep=like[thisuser].length;
		sign(id[thisuser],thisuser);  //直接标记为完成
		//$("#statu"+thisuser).html("状态:已签到");
		if(thisuser+1==id.length) return;
		scroll(0,document.getElementById("userjid"+(thisuser)).offsetTop);
	}
	else 
	{
		dep=0;
		nofail=true;
		intervalid=setInterval("sign("+id[thisuser]+","+thisuser+");",1000);
	}
	return;
}



function getlike(uid)
{
	//得到指定人所有的喜爱的吧
	var ret;
	var url="gettb.php?id="+uid;
	$.getJSON(url,"",function(data){
		ret=data;
	});
	return ret;
}

/**********************************************************************


						I dont know.


**********************************************************************/


var wannadel=-1;

function colorboxinit()
{
	$(".ajax").colorbox({width:400,height:300});
	$(".inline").colorbox({inline:true, width:350,height:300});
	
}

function setpsw(p)
{
	$.getJSON("pubpsw.php","checkmode=1&psw="+escape(p),function(data){
		if(data["status"]==1) {$("#pswstat").html("密码正确");$("#inputpswlink").html("密码正确");
		$("#setlinklayer").attr("class","show");
		$(".AdminDisplay").css("display","block");
		window.parent.$.colorbox.close();
		}
		else $("#pswstat").html("密码错误");
	}
	);
	psw=p;
}

function showset()
{
	$.colorbox({inline:true,href:"#inline_adduser",width:400,height:500,onClosed:function(){
		frmadd.desc.value="";
		frmadd.cookies.value="";
		frmadd.filter.value="";
	}});
	$("#inline_adduser_status").html("");
	frmadd.desc.value="";
	frmadd.cookies.value="";
}

function deluserdialog(jid)
{
	if(psw=="") return false;
	wannadel=jid;
	$.colorbox({inline:true,href:"#inline_deluserprompt",width:400,height:300,onClosed:notsuretodel});
	$("#deluserprompt_userinfo").html("<p>别称："+desc[jid]+"</p><p>序号："+id[jid]+"</p>");
	$("#deluser_status").html("");
}

function notsuretodel()
{
	wannadel=-1;
	//window.parent.$.colorbox.close();
}

function deleteidelement(jid)
{
	var id2=new Array();
	var desc2=new Array();
	var like2=new Array();
	var i=id.length;
	while(jid!=i)
	{
		i--;
		id2.push(id.pop());
		desc2.push(desc.pop());
		like2.push(like.pop());
	}
	for(i=1;i<id2.length;i++)
	{
		id.push(id2[i]);
		desc.push(desc2[i]);
		like.push(like2[i]);
	}
}

function suretodel()
{
	//$.colorbox({href:"delete.php?psw="+psw+"&id="+id[wannadel],width:400,height:300,onClosed:notsuretodel});
	//notsuretodel()
	$("#deluser_status").html("<img src=images/loading.gif>");
	$.getJSON("delete.php","psw="+psw+"&id="+id[wannadel],function(data){
		if(data["status"]==1){
			$("#deluser_status").html("成功删除");
			setTimeout("window.parent.$.colorbox.close();",1000);
			deleteidelement(wannadel);
			document.getElementById("users").removeChild(document.getElementById("userjid"+wannadel));
			$("#usercount").html(id.length);
			scroll(0,document.getElementById("userjid"+(wannadel-1)).offsetTop);
		}
		else
		{
			$("#deluser_status").html("<font color=red>删除失败，请重试</font>");
		}
	});
}

function addusercookies()
{
	$.post("set.php?psw="+psw,$("#frmadd").serialize(),function(data){
		if(data["status"]){
			$("#inline_adduser_status").html("成功添加用户");
			setTimeout("window.parent.$.colorbox.close();",1000);
			id.push(data["id"]);desc.push(data["desc"]);
			var ret=addtab(id.length-1);
			tabs+=ret;
			$("#users").html(document.getElementById("users").innerHTML+ret);
			$("#usercount").html(id.length);
			scroll(0,document.getElementById("userjid"+(id.length-1)).offsetTop);
		}
		else
		{
			$("#inline_adduser_status").html("添加用户失败: "+data["info"]);
		}
	},"json")
}

function showedit(uid)
{
	uid=id[uid];
	$("#inline_edituser_status").html("");
	$("#inline_edituser_tip_id").html(uid);
	$("#edit_uid").val(uid);
	$.colorbox({inline:true,href:"#inline_edituser",width:400,height:540,onClosed:function(){
		frmedit.desc.value="";
		frmedit.cookies.value="";
		frmedit.id.value="";
		frmedit.filter.value="";
	}});
	frmedit.desc.value="加载中...";
	$.getJSON("getinfo.php",{psw:psw,id:uid},function(data){
		frmedit.desc.value=data["desc"];
		frmedit.cookies.value=data["cookies"];
		frmedit.filter.value=data["filter"];	
	});
}

function edituser()
{
	uid=frmedit.id.value;
	for(var i=0;i<id.length;i++)if(id[i]==uid)break;
	jud=function(data){
		if(data["status"]==1)
		{
			$("#inline_edituser_status").html("成功添加用户");
			setTimeout("window.parent.$.colorbox.close();",1000);
			$.getJSON("getinfo.php",{psw:psw,id:uid},function(data){desc[i]=data["desc"];filter[i]=data["filter"];});
			hlkh=addtab(i);
			document.getElementById("userjid"+i).outerHTML=hlkh;
			$(".AdminDisplay").css("display","block");
			scroll(0,document.getElementById("userjid"+i).offsetTop);
		}
		else
		{
			$("#inline_edituser_status").html("失败，info:"+data["info"]);
		}
	};
	$.post("edit.php?psw="+psw,$(frmedit).serialize(),jud,"json");
}
	
function addtab(jid)
{
	var tablewidth="600";
	if(document.body.clientWidth<=600) tablewidth="100%";
	if(typeof(like[jid])==='undefined')like.push(new Array());
	if(!issigned[jid])like[jid]=getlike(id[jid]);
	else like[jid]=Array('signok');
	try{console.debug('jid:'+jid+',id:'+id[jid]+'=>\n'+like[jid]);}catch(err){};
	var ret="<div id=userjid"+jid+">";
	if(!issigned[jid])
	{
	if(typeof(like[jid])!="undefined"&&like[jid].length!=0) 
	{
		ret+="<table border=0 width="+tablewidth+"><tr class=h><td width=60%>"+desc[jid]+"-"+like[jid].length+"符合<span class=AdminDisplay style=display:none>&nbsp;<a href='javascript:void(0);' onclick='showedit("+jid+")'>编</a>&nbsp;<a href='javascript:void(0);' onclick='deluserdialog("+jid+");'>删</a></span></td><td id=statu"+jid+">状态↓-filter:"+filter[jid]+"</td></tr>";
		for(var j=0;j<like[jid].length;j++)
		{
			var tmptdd="<tr class=o>";
			if(j%2==1)tmptdd="<tr class=d>"
			ret+=tmptdd+"<td class=tbname><a onclick='sign("+id[jid]+","+jid+","+j+");'>&nbsp;&nbsp;"+like[jid][j]+"</a></td><td id=statu"+jid+"t"+j+"></td></tr>";
		}
	}
	else
	{
		ret+="<table border=0 width="+tablewidth+"><tr class=h><td width=60%><div>"+desc[jid]+"-无符合，失败<span class=AdminDisplay style=display:none>&nbsp;<a href='javascript:void(0);' onclick='showedit("+jid+")'>编</a>&nbsp;<a href='javascript:void(0);' onclick='deluserdialog("+jid+");'>删</a></span></div></td><td id=statu"+jid+">状态↓-filter:"+filter[jid]+"</td></tr>";
	}}else{
		ret+="<table border=0 width="+tablewidth+"><tr class=hs><td width=60%><div>"+desc[jid]+"-已标记为签到完成<span class=AdminDisplay style=display:none>&nbsp;<a href='javascript:void(0);' onclick='showedit("+jid+")'>编</a>&nbsp;<a href='javascript:void(0);' onclick='deluserdialog("+jid+");'>删</a></span></div></td><td id=statu"+jid+">状态↓-filter:"+filter[jid]+"</td></tr>";	
	}
	ret+="</table><br></div>";
	return ret;
}

var id=new Array(),desc=new Array(),like=new Array(),issigned=new Array(),filter=new Array();
var finishnum=0;
var thisuser=-1;
var intervalid=-1;
var dep;
<?php require "../Conn/Conn.php";?>
<?php
header("Content-Type: text/html; charset=utf-8");
$murl=urlencode("tonight i feel close to you.mp3");
$result = mysql_query("select * from tb_user ORDER BY id");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
<META http-equiv=Content-Type content="text/html; charset=utf-8">
<title>小森森的自动签到工具</title>
<script src="jquery-1.9.1.min.js"></script>
<script src="oldsign.js"></script>
<script>
$(function()
{
	$.ajaxSetup({async:false});
	setTimeout("init();",10);
	colorboxinit();
	//setTimeout("location.href='//zhs.svfree.net/tb';",4000)
}
)
function init()
{
	//初始化
<?php
function istoday($timestr)
{
	if(date("Y-m-d")==$timestr)
	{
		return true;
	}
	else
	{
		return false;
	}
}
while($row = mysql_fetch_array($result))
{
?>
	id.push(<?=str_replace("\\","\\\\",$row["id"])?>);
	desc.push("<?=str_replace("\\","\\\\",$row["desc"])?>");
	issigned.push(<?=istoday($row["last"])?"true":"false"?>);
	filter.push("<?=str_replace("\\","\\\\",htmlspecialchars($row["filter"],ENT_QUOTES,"UTF-8"))?>");
<?php
}
mysql_close($con);
?>
	init2();
	$("#allstat").html("[New]界面好鹾。。看来需要更新一下<br>点击『开始签到』……<br><a href=javascript:void(0); onclick=inputpsw() alt=开始签到>开始签到</a><br>等待用户选择，顺便介绍：小森森这个版本的代签到可以+6经验哦~……<br>如需代签到请联系我；仅需提供cookies而无需帐号密码");
	$("#loading").html("当前共有<span id=usercount>"+id.length+"</span>个账户。");
	$("#users").html(tabs);
}

function init2()
{
	if(document.body.clientHeight>=250) $("#maindiv").addClass("divdown");
	for(var i=0;i<id.length;i++) tabs+=addtab(i);
}

function inputpsw()
{
	//已去使能；被标记是否已处理功能代替  zhs 小森森
	//if(psw=="")psw=prompt("请输入密码","");

	
	setTimeout("allsign();",1000);

	//allsign();
}
function createMP3player(url)
{
	var smpurl="singlemp3player.swf?file="+encodeURI(url)+"&autoStart=true&backColor=ffffff&frontColor=aaaaee&songVolume=100&showDownload=false&repeatPlay=true";
	var smphtml='<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="300" height="60" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab"><param name="movie" value="'+smpurl+'" /><param name="wmode" value="transparent" /><embed wmode="transparent" width="300" height="60" src="'+smpurl+'" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" /></object>';
	return smphtml;
}
var tabs="";
var psw="";
</script>
<style type="text/css">
body {
	text-shadow:-4px  3px 3px  #aaaaee;
	font-family:"微软雅黑", "黑体";
}
.tbname {
	font-size: 9pt;
}
.d {
	background-color:#ccccff;
}
.o {
	background-color:#EBEBEB;
}
.h {
	background-color:#eecccc;
	font-weight:bold;
}
.hs {
	background-color:#390;
	color:#ffffff;
	font-weight:bold;
}
.hf {
	background-color:#FF8C00;
	color:#ffffff;
	font-weight:bold;
}
.st0 {
	color:#390;
	font-size: 9pt;
}
.st1 {
	color:#F93;
	font-size: 9pt;
}
.st2 {
	color:#ff0000;
	font-size: 9pt;
	font-weight:bold;
}
.wh {
	color:#ffffff;
}
.hide {
	display:none;
}
.show {
	display:block;
}
.divdown {
	
}
</style>
<script src="jquery.colorbox-min.js"></script>
<link rel="stylesheet" href="colorbox.css" />
</head>
<body>
<? if(!isset($_GET["n"])){ ?>
<div id="soundplayerlayer" align="center"></div>
<script>document.getElementById("soundplayerlayer").innerHTML=createMP3player("tonight i feel close to you.mp3");</script>
<?php } ?>
<script>
if(!-[1,]&&!window.XMLHttpRequest) $("#soundplayerlayer").html("您在使用IE6浏览器，太老了太老了太老了……");
</script>

<div align="center" id="maindiv">
<div id="inputpswlink" class="show">
<p><a class='inline' href="#inline_content">Input Admin Psw</a></p>
</div>
<div id="setlinklayer" class="hide">
<p><a onclick="showset();" href="javascript:void(0);">点击此处添加新用户</a></p>
</div>
<br /><br />

<div id="loading"><img src="images/loading.gif" /></div><br><br>
<div id="allstat"><img src="images/loading.gif" /></div><br><br>
<div id="users"><img src="images/loading.gif" /></div>
<br>
<div id="bottomcl"><hr><p>Written By <a href="http://www.baidu.com/p/%E8%BF%9E%E4%BA%91%E5%B0%8F%E6%A3%AE%E6%A3%AE?from=tieba" target="_blank"><b>连云小森森</b></a></p><p><a href="/">返回lyxss.tk首页</a></p><br></div>
<br>
<div class="hide">
			<div id='inline_content' style='padding:10px; background:#fff;'>
			<p><strong>请输入密码：</strong>			</p>
			<form id="frmpsw" name="frmpsw" method="post" action="" onsubmit="return false;">
			  <p>密码：
  <input type="text" name="textfield" id="textfield" onkeypress="if(event.keyCode==13) setpsw(this.value);"/>
			    <div id="pswstat"></div>
		      </p>
			  <p>
			    <input type="button" name="button" id="button" value="提交" onclick="setpsw(frmpsw.textfield.value);" />
			  </p>
			</form>
			
  </div>
  			<div id='inline_deluserprompt' style='padding:10px; background:#fff;'>
			<div id='deluser_status'></div>
			<p><b>删除用户？</b></p>
			<div id="deluserprompt_userinfo"></div>
            <p><a href="javascript:;" onclick="suretodel();">确认</a></p>
            <p><a href="javascript:;" onclick="window.parent.$.colorbox.close();">算了不删除了……取消……</a></p>
            
  </div>
  <div id="inline_adduser" style='padding:10px; background:#fff;'>
  <div align="center">
  <div id="inline_adduser_status"></div>
  <form id="frmadd" name="frmadd" method="post" onsubmit="return false;" action="set.asp" accept-charset="gbk">
    昵称<p>
      <input type="text" name="desc" id="desc" />
    </p>
    登录状态<p>
      <textarea name="cookies" id="cookies" cols="40" rows="5"></textarea>
    </p>
    过滤器($fr:默认1,$tbname,$lv,$ex;gettb使能)<p>
      <textarea name="filter" id="filter" cols="40" rows="5">$fr=1;</textarea>
    </p>
    <p>
      <input type="submit" name="button" id="button" value="提交" onclick="addusercookies();"/>
    </p>
  </form>
</div>
  </div>
<div id="inline_edituser" style='padding:10px; background:#fff;'>
<div align="center">
<div id="inline_edituser_status"></div>
<div id="inline_edituser_tip">编辑的用户id：<span id="inline_edituser_tip_id"></span></div>
  <form id="frmedit" name="frmedit" method="post" onsubmit="return false;" >
  <input type="hidden" name="id" id="edit_uid" value="" />
    昵称<p>
      <input type="text" name="desc" id="desc" />
    </p>
    登录状态<p>
      <textarea name="cookies" id="cookies" cols="40" rows="5"></textarea>
    </p>
    过滤器($fr:默认1,$tbname,$lv,$ex;gettb使能)<p>
      <textarea name="filter" id="filter" cols="40" rows="5">$fr=1;</textarea>
    </p>
    <p>
      <input type="submit" name="button" id="button" value="提交" onclick="edituser();"/>
    </p>
  </form>
</div>
		</div>
</div>
</body>
</html>

<?php exit();?>
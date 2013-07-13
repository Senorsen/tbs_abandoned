<?php
require "../Conn/Conn.php";
$result=mysql_query("select * from tb_settings where location='all' or location='tbsign'");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="Keywords" content="lyxss,连云小森森,lyxss.tk,zhs,zhs490770"/>
<script src="/js/jquery.js"></script>
<script src="/js/index.max.js"></script>
<script src="/js/osustyle.js"></script>
<script src="newsign.js"></script>
<link href="/css/index.max.css" rel="stylesheet" type="text/css" />
<link href="/css/blogstyle.css" rel="stylesheet" type="text/css" />
<link href="/css/osustyle.css" rel="stylesheet" type="text/css" />
<script>_lyxss_sv_set={<?php $isstart=0;while($row=mysql_fetch_array($result)){if($isstart)echo ",";$isstart=1;echo $row["key"];?>:"<?php echo $row["value"];?>"<?php }?>};</script>
<script>$(function(){lyxssii();lyxssiw();osu_word="流云昔时殇……";osuinit();signinit();});</script>
<title>流云昔时殇</title>
</head>

<body>
<div id="iealert" style="display:none"></div>
<div id="header">
<div id="lyxss-title" class="opt-mev" maxo="0.9" mino="0.5"></div>
<div id="lyxss-signature" class="opt-mev" mino="0.5"></div>
</div>
<div id="container">

<div id="centerNav"><p><a class="item" href="/tb/old.php">老版本贴吧签到(新版本建设中)</a></p><br /></div>

</div>
<div id="copyright" class="opt-mev" mino="0.2" napp="1"><div class="cr-word" id="cr-word"><a id="copyright-lyxss-link" href="http://www.baidu.com/p/%E8%BF%9E%E4%BA%91%E5%B0%8F%E6%A3%AE%E6%A3%AE?from=tieba" target="_blank" title="新窗口中打开">连云小森森(@tb)&nbsp;&nbsp;lyxss</a>&nbsp;&nbsp;&nbsp;Copyright 2013&nbsp;&nbsp;&nbsp;</div></div>
</body>
</html>

<?php exit();?>
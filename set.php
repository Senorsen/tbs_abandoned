<?php
require "../Conn/Conn.php";
require "pubpsw.php";
?>
<?php
header("Content-Type: text/html; charset=utf-8");
$desc=$_POST["desc"];
$cookies=$_POST["cookies"];
$filter=$_POST["filter"];
$desc=str_replace("'","\\'",$desc);
$desc=str_replace("\\","\\\\",$desc);
$desc=str_replace("'","\\'",$cookies);
$desc=str_replace("\\","\\\\",$cookies);
$desc=str_replace("'","\\'",$filter);
$desc=str_replace("\\","\\\\",$filter);

if(!mysql_query("insert into tb_user(`id`,`desc`, `cookies`, `last`,`filter`) 
values (NULL,'$desc','$cookies','new','$filter')"))
{
	echo '{"status":0,"info":"未知错误:<br>'.HtmlEncode(mysql_error()).'"}';
}
else
{
	echo '{"status":1,"id":'.mysql_insert_id().',"desc":"'.$desc.'"}';
}
?>
<?php exit();?>
<?php
function HtmlEncode($fString)
{
if($fString!="")
{
	$fString = str_replace( '"', '&quot;',$fString);
	$fString = str_replace( '>', '&gt;',$fString);
	$fString = str_replace( '<', '&lt;',$fString);
	$fString = str_replace( chr(32), '&nbsp;',$fString);
	$fString = str_replace( chr(13), ' ',$fString);
	$fString = str_replace( chr(10) & chr(10), '<br>',$fString);
	$fString = str_replace( chr(10), '<BR>',$fString);
}
     return $fString;
}
function EncodeHtml($fString)
{
if($fString!="")
{
     $fString = str_replace("&gt;" , ">", $fString);
     $fString = str_replace("&lt;", "<", $fString);
     $fString = str_replace("&nbsp;",chr(32),$fString);
     $fString = str_replace("",chr(13),$fString);
     $fString = str_replace("<br>",chr(10) & chr(10),$fString);
     $fString = str_replace("<BR>",chr(10),$fString);
}
     return $fString;
}
?>
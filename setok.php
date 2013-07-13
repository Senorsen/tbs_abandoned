<? require "../Conn/Conn.php";?>
<?
header("Content-Type: text/html; charset=utf-8");
if(!isset($_GET["id"]))
{
	die('{"status":0,"info":"ERR:INVALID_ID_NULL"}');
}
else
{
	$id=$_GET["id"];
}
if(!preg_match('/^\d+$/',$id))
{
	die('{"status":0,"info":"ERR:INVALID_ID_DOES_NOT_MATCH_/^\d+$/_YOU_KNOW"}');
}
$setmode=$_GET["t"];
$wt=$setmode=='enable'?date('Y-m-d'):'disable';
if(mysql_query("update tb_user set last='$wt' where id=$id"))
{
	echo '{"status":1,"info":"ERR:SUCCESS"}';
}
else
{
	die('{"status":0,"info":"ERR:MYSQL_UPDATE_ERROR:'.mysql_error().'"}');
}
mysql_close($con);

?>
<? exit();?>
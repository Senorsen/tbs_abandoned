<?
require "../Conn/Conn.php";
require "pubpsw.php";
?>
<?
header("Content-Type: text/html; charset=utf-8");
$id=$_GET["id"];
if(!mysql_query("delete from tb_user where id=$id"))
{
	echo '{"status":0,"info":"未知的mysql错误:"'.mysql_error().'}';
}
else
{
	echo '{"status":1}';
}
?>
<? exit();?>
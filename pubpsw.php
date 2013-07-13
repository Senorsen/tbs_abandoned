<?
require "../Conn/Conn.php";
$result=mysql_query("select * from `tb_settings` where `key`='tbpsw'");
$row=mysql_fetch_array($result);
$psw=$row["value"];
if(isset($_GET["psw"]))
{
	if($_GET["psw"]!=$psw)
	{
		echo '{"status":0}';
		exit();
	}
	else if(isset($_GET["checkmode"]))
	{
		echo '{"status":1}';
	}
}
?>
<?php require "../Conn/Conn.php";
require "pubpsw.php";
?>
<?php
header("Content-Type: text/html; charset=utf-8");
$id=$_GET["id"];
$result=mysql_query("select * from tb_user where `id`=$id");
$row=mysql_fetch_array($result);
$cookies=$row["cookies"];
$desc=$row["desc"];
$filter=$row["filter"];
$o=(object)array(
	"desc" => $desc,
	"cookies" => $cookies,
	"filter" => $filter
);
echo json_encode($o,JSON_HEX_TAG|JSON_HEX_APOS|JSON_HEX_QUOT|JSON_HEX_AMP|JSON_UNESCAPED_UNICODE);
mysql_close($con);

?>
<?php exit();?>
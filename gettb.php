<?php require "../Conn/Conn.php";?>
<?php
header("Content-Type: text/html; charset=utf-8");
if(!isset($_GET["id"]))
{
	die('咦，id跑哪去啦？(这儿干脆连json都不用了)');
}
else
{
	$id=$_GET["id"];
}
if(!preg_match('/^\d+$/',$id))
{
	die('咦，有几个字符我不认得哦。。(这儿干脆连json都不用了)');
}
$result=mysql_query("select * from tb_user where id=$id");
$row = mysql_fetch_array($result);
if(!$row)
{
	die('错误：不存在的记录');
}
$cookies=$row["cookies"];
$desc=$row["desc"];
$filter=$row["filter"];
mysql_close($con);

$str=curlFetch("http://wapp.baidu.com/m?tn=bdFBW","$cookies; WIFI_SF=1368757590;");
//echo $str;
preg_match('/(?<=width="100%">).*(?=<\/table>)/',$str,$matches);
$str=$matches[0];
//echo "------------------------------------------------=-=-=----------------$str";
//exit();
preg_match_all('/[^<>]+(?=<\/a>)/',$str,$matches);
preg_match_all('/等级(\d+)/',$str,$lv_mt);
preg_match_all('/经验值(\d+)/',$str,$exp_mt);
$i=0;

echo "[";
$isstart=0;
while(isset($matches[0][$i]))
{
	if(UserFilter($matches[0][$i],intval($lv_mt[1][$i]),intval($exp_mt[1][$i]),$filter))
	{
		if($isstart) echo ",";
		$isstart=1;
		echo '"'.$matches[0][$i].'"';
	}
	$i++;
}
echo "]";
//print_r(array("Cookie: $cookies"));
function UserFilter($tbname,$lv,$ex,$filter){$fr=1;eval($filter);return $fr;};

?>
<?php
function curlFetch($url, $cookie = "", $referer = "", $data = null)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回字符串，而非直接输出
	curl_setopt($ch, CURLOPT_HEADER, false);   // 不返回header部分
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);   // 设置socket连接超时时间
	if (!empty($referer))
	{
		curl_setopt($ch, CURLOPT_REFERER, $referer);   // 设置引用网址
	}
	if (!empty($cookie))
	{
		curl_setopt($ch,CURLOPT_HTTPHEADER,array("Cookie: $cookie"));
	}
	
	if (is_null($data))
	{
		// GET
	}
	else if (is_string($data))
	{
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
		// POST
	}
	else if (is_array($data))
	{
		// POST
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	}
	//set_time_limit(120); // 设置自己服务器超时时间
	$str = curl_exec($ch);
	curl_close($ch);
	return $str;
}
?>
<?php exit();?>
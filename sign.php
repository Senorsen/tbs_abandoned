<?php require "../Conn/Conn.php";?>
<?php
header("Pragma: no-cache");
header("Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=utf-8");
ignore_user_abort(true);
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
$tb=unescape($_GET["tb"]);
$cookies=$row["cookies"];
$desc=$row["desc"];
mysql_close($con);
$useragent="Dalvik/1.6.0 (Linux; U; Android 4.1.1; MI 2S MIUI/3.8.30)";
$tbvurl="http://wapp.baidu.com/f?kw=$tb";
$myheader2=array("Cookie: $cookies;");
$str=curlFetch($tbvurl,$myheader2);
//echo $str;
//print_r($myheader);
if(preg_match('/已签到<\/span>/',$str))
{
	myexit(0,"已签到",$desc,$tb);
}
if(!preg_match('/<td style="text-align:right;"><a href="/',$str))
{
	myexit(1,"去使能",$desc,$tb);
}
//echo $str;
preg_match('/(?<=<td style="text-align:right;"><a href=")[^">]+/',$str,$matches);
//               /mo/q-----2-3-0--/
$oldurl=$matches[0];
preg_match('/sign\?tbs=(.+)&amp;fid=(.+)&amp;/',$oldurl,$matches);
$tbs=$matches[1];
$fid=$matches[2];
//$tbsurl='http://wapp.baidu.com'.'/mo/q-----2-3-0--/'.str_replace('&amp;','&',$matches[0]);
$tbsurl='http://c.tieba.baidu.com/c/c/forum/sign';			//8经验！卧槽
//echo $tbsurl;
$myheader=array();
$myheader=array("Cookie: $cookies;","User-Agent: Mozilla/5.0 (iPhone; CPU iPhone OS 5_0 like Mac OS X) AppleWebKit/534.46 (KHTML, like Gecko) Version/5.1 Mobile/9A334 Safari/7534.48.3 TiebaClient/1.2.1.17");
$cookies_spl=$cookies.';';
preg_match('/BDUSS=(.+?);/',$cookies_spl,$match_ck);
$bduss=$match_ck[1];
$postdata=array(
			"BDUSS"=>$bduss,
			"_client_id"=>"wappc_1378485686660_60",
			"_client_type"=>"2",
			"_client_version"=>"4.2.2",
			"_phone_imei"=>"540b43b59d21b7a4824e1fd31b08e9a6",
			"fid"=>$fid,
			"kw"=>$tb,
			"net_type"=>3,
			"tbs"=>$tbs
			);
$strsign='';
foreach($postdata as $t=>$v)
{$strsign.=$t."=".$v;}
$md5sign=strtoupper(md5($strsign."tiebaclient!!!"));
$postdata['sign']=$md5sign;
			//var_dump($postdata);
			//echo json_encode((object)$postdata);
//echo http_build_query($postdata);
$str=curlFetch($tbsurl,$myheader,"",$postdata);
//echo $str;
$obj=json_decode($str,true);
if(is_null($obj)) myexit(2,'未知错误：返回值NULL',$desc,$tb);
//var_dump($obj);
if($obj["error_code"]==0)
{
	myexit(0,'增加'.$obj["user_info"]["sign_bonus_point"].'经验值',$desc,$tb);
}
else
{
	myexit(2,'未知错误'.$obj["error_code"].','.$obj["error_msg"],$desc,$tb);
}
?>
<?php
function myexit($retval,$retinfo,$desc,$tb)
{
	echo '{"desc":"'.$desc.'","tb":"'.$tb.'","status":"'.$retval.'","returnval":"'.$retinfo.'"}';
	exit();
}
function curlFetch($url, $addheader=null, $referer = "", $data = null)
{
	$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // 返回字符串，而非直接输出
	curl_setopt($ch, CURLOPT_HEADER, false);   // 不返回header部分
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 120);   // 设置socket连接超时时间
	if (!empty($referer))
	{
		curl_setopt($ch, CURLOPT_REFERER, $referer);   // 设置引用网址
	}
	if (!is_null($addheader))
	{
		curl_setopt($ch,CURLOPT_HTTPHEADER,$addheader);
		//print_r($addheader);
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
		//echo http_build_query($data);
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
<?php
function escape($str) {
	preg_match_all ( "/[\xc2-\xdf][\x80-\xbf]+|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}|[\x01-\x7f]+/e", $str, $r );
	//匹配utf-8字符， 
	$str = $r [0];
	$l = count ( $str );
	for($i = 0; $i < $l; $i ++) {
		$value = ord ( $str [$i] [0] );
		if ($value < 223) {
			$str [$i] = rawurlencode ( utf8_decode ( $str [$i] ) );
		//先将utf8编码转换为ISO-8859-1编码的单字节字符，urlencode单字节字符. 
		//utf8_decode()的作用相当于iconv("UTF-8","CP1252",$v)。 
		} else {
			$str [$i] = "%u" . strtoupper ( bin2hex ( iconv ( "UTF-8", "UCS-2", $str [$i] ) ) );
		}
	}
	return join ( "", $str );
}
function unescape($str) {
	$ret = '';
	$len = strlen ( $str );
	for($i = 0; $i < $len; $i ++) {
		if ($str [$i] == '%' && $str [$i + 1] == 'u') {
			$val = hexdec ( substr ( $str, $i + 2, 4 ) );
			if ($val < 0x7f)
				$ret .= chr ( $val );
			else if ($val < 0x800)
				$ret .= chr ( 0xc0 | ($val >> 6) ) . chr ( 0x80 | ($val & 0x3f) );
			else
				$ret .= chr ( 0xe0 | ($val >> 12) ) . chr ( 0x80 | (($val >> 6) & 0x3f) ) . chr ( 0x80 | ($val & 0x3f) );
			$i += 5;
		} else if ($str [$i] == '%') {
			$ret .= urldecode ( substr ( $str, $i, 3 ) );
			$i += 2;
		} else
			$ret .= $str [$i];
	}
	return $ret;
}
?>
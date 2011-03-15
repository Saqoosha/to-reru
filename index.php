<?
ini_set('display_errors', 'Off');

header('Content-type: text/html; charset=Shift_JIS');

if (isset($_GET['address'])) {
	$req = 'http://maps.google.com/maps/api/geocode/xml';
	$req .= '?address='.urlencode(mb_convert_encoding($_GET['address'], 'UTF-8', 'SJIS'));
	$req .= '&sensor=false';
	$xml = simplexml_load_file($req) or die('XML parsing error');
	if ($xml->status == 'OK') {
	  $location = $xml->result->geometry->location;
	  $lat = (string)$location->lat;
	  $lng = (string)$location->lng;
	}
} else {
	$lat = $_GET["lat"];
	$lng = $_GET["lng"];
}

if($lat == ""){
  $lat = "38.2596";
}
if($lng == ""){
  $lng = "140.8799";
}


//縮尺率
$z = $_GET["z"];
if($z ==""){
  $z = 15;
}

//移動後の位置を計算
$top = adjust($lng, $lat, 0, -100, $z);
$bottom = adjust($lng, $lat, 0, 100, $z);
$left = adjust($lng, $lat, -100, 0, $z);
$right = adjust($lng, $lat, 100, 0, $z);

$url = "http://saqoo.sh/a/labs/to-reru/?lat=" . $lat . "&lng=" . $lng;
$mailto = "mailto:?body=" . urlencode($url);

/*
  function adjust
  
  $x:中心の経度
  $y:中心の緯度
  $deltaX:ずらしたい距離(ピクセル単位)
  $deltaY:ずらしたい距離(ピクセル単位)
  $z:ズーム
  
  戻り値
  array("x"=>"移動後の経度","y"=>"移動後の緯度");
*/
function adjust($x,$y,$deltaX,$deltaY,$z){
	$offset = 268435456;
	$radius = $offset / pi();
	$x = ((round(round($offset + $radius * $x * pi()/180)+($deltaX << (21-$z))) - $offset) / $radius) * 180 / pi();
	$y = (pi() / 2 - 2 * atan(exp((round(round($offset - $radius * log((1 + sin($y * pi() / 180))/(1 - sin($y * pi() / 180))) / 2)+($deltaY << (21-$z))) - $offset) / $radius))) * 180 / pi();
	$xy = array("x" => round($x * 10000) / 10000, "y" => round($y * 10000) / 10000);
	return $xy;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=Shift_JIS">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>東北地方太平洋沖地震：通行実績情報（携帯版）</title>
</head>
<body>
<center>
	<form action="index.php" method="get">
		<input type="text" name="address"></input>
		<input type="submit" value="検索">
	</form>
<img src="http://saqoo.sh/a/labs/to-reru/mapimg.php?lat=<?=$lat?>&lng=<?=$lng?>&z=<?=$z?>" />
<br />
<a href="?lat=<?=$left["y"]?>&lng=<?=$left["x"]?>&z=<?=$z?>" accesskey="4">4:←</a>
<a href="?lat=<?=$top["y"]?>&lng=<?=$top["x"]?>&z=<?=$z?>" accesskey="2">2:↑</a>
<a href="?lat=<?=$bottom["y"]?>&lng=<?=$bottom["x"]?>&z=<?=$z?>" accesskey="8">8:↓</a>
<a href="?lat=<?=$right["y"]?>&lng=<?=$right["x"]?>&z=<?=$z?>" accesskey="6">6:→</a>
<br>
<a href="mailto:<?=$mailto?>">このﾍﾟｰｼﾞをﾒｰﾙする</a>
</center>
</body>
</html>
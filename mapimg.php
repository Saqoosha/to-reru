<?php
header('Content-type: image/png');
ini_set('display_errors', 'Off');

$lat = $_GET["lat"];
$lng = $_GET["lng"];

if($lat == ""){
  $lat = "38.259638";
}
if($lng == ""){
  $lng = "140.879902";
}

putenv("DISPLAY=:0.0");
$png = tempnam('img', 'hoge') . '.gif';
$cmd = implode(' ', array('/usr/local/bin/phantomjs', 'mapimg.js', $lat, $lng, $png));
exec($cmd, $out, $ret);
if ($ret) {
	readfile('auau.gif');
} else {
	readfile($png);
}
unlink($png);

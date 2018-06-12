<?php
header('Content-Type: text/html; charset=utf-8');
$tag = $_GET['tag'];
//$server = $_SERVER['SERVER_NAME'];
$server ='183.89.76.57:85';
$data = file_get_contents('http://'.$server.'/findAsset/getContent.php?tag='.$tag);
//echo $data;
$ip = gethostbyname(parse_url('http://androidserver.ddns.net/', PHP_URL_HOST));
echo $ip;

?>
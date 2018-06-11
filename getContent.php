<?php
header('Content-Type: text/html; charset=utf-8');
//$server = $_SERVER['SERVER_NAME'];
$server ='183.89.76.57:85';
$data = file_get_contents('http://'.$server.'/findAsset/getContent.php?tag=BBD1799688');
echo $data;

?>
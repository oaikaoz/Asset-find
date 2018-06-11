<?php
$server = $_SERVER['SERVER_NAME'];
$data = file_get_contents('http://'.$server.'/findAsset/getContent.php?tag=BBD1799688');
echo $data;

?>
<?php
    header('Content-Type: text/html; charset=utf-8');
    $tag = $_GET['tag'];
    //$server = $_SERVER['SERVER_NAME'];
    //$server ='183.89.76.57:85';
    $server ='androidserver.ddns.net';
    $data = file_get_contents('http://'.$server.'/findAsset/getContent.php?tag='.$tag);
    
    echo $data;                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                 
?>
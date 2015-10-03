<?php

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

$endpoint = "http://api.majestic.com/api/json?app_api_key=AA3D7FEDA8E34089FB821FF4A2A97EBA&cmd=GetRefDomains&item0=".$_GET['url']."&Count=10000&datasource=fresh";

$json = file_get_contents($endpoint);
echo $json;


?>



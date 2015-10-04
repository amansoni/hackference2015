<?php

header('Content-Type: application/json');
$file='twitter.txt';
$json = json_decode(file_get_contents($file), true);
echo $json;

?>

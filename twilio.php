<?php
    header("content-type: text/xml");

$file = "twitter.txt";

$myfile = fopen($file, "a") or die("Unable to open file!");
// get twitter handle from message
$text = $_REQUEST['Body'];
$from = $_REQUEST['From'];
$data = "@amansoni\n";
fwrite($myfile, "\n". $data);
fclose($myfile);

$file = "log.txt";
$myfile = fopen($file, "a") or die("Unable to open file!");
$debug = var_export($_POST, true);
fwrite($myfile, "\n". $debug);
fclose($myfile);

?>
<?xml version="1.0" encoding="UTF-8"?>
<Response>
    <Message>Hello, Hackferencer! We got your twitter as: <?php echo $data ?></Message>
</Response>

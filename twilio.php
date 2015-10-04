<?php
//  header("content-type: text/xml");

$file = "twitter.txt";

$myfile = fopen($file, "a") or die("Unable to open file!");
// get twitter handle from message
$data = $_REQUEST['Body'];
$from = $_REQUEST['From'];
//$data = "Hey this is my username @amansoni and a little more\n";
//$data = preg_replace('/(^|[^a-z0-9_])@([a-z0-9_]+)/i', '$1@$2', $data);

preg_match_all('/@([A-Za-z0-9_]{1,15})/', $data, $usernames);
$data = $usernames[1][0] + ', ' + $from;


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
    <Message>Hello, Hackferencer! We got your twitter as: <?php echo $usernames[1][0] ?></Message>
</Response>

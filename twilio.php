<?php
    header("content-type: text/xml");
    echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";

$file = "twitter.txt";
$fh = fopen($file, 'a') or die("can't open file");
// get twitter handle from message
$data = "@amansoni\n";
fwrite($fh, $data);
fclose($fh);

?>
<Response>
    <Message>Hello, Hackferencer! We've got your twitter as: <?php echo $data ?></Message>
</Response>

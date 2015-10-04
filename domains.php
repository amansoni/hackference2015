<?php


header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");


try {
$db = new PDO('mysql:host=localhost;dbname=hackference2015;charset=utf8', 'root', 'root');

    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }

    $stmt = $db->prepare("SELECT * FROM refdomains AS ref
     JOIN domains AS d ON d.id=ref.refdomainid AND d.ip > '' JOIN locations as loc ON loc.domainid = d.id LIMIT :start , :end;");

    $from =  ((int)trim($_GET['page']) - 1) * 100;
    $to =  100;

    $stmt->bindParam(':start',$from, PDO::PARAM_INT);
    $stmt->bindParam(':end', $to, PDO::PARAM_INT);


    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);


    echo json_encode($rows);


<?php

require 'vendor/autoload.php';

use GeoIp2\Database\Reader;


$start = microtime(true);

header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

set_time_limit(3600);
ini_set('memory_limit','512M');

$domains = array(
    array('domain'=> 'https://twitter.com/caleuanhopkins', 'ip' => ''),
    array('domain'=> 'https://twitter.com/dan_jenkins', 'ip' => ''),
    array('domain'=> 'https://twitter.com/etiene_d', 'ip' => ''),
    array('domain'=> 'https://twitter.com/hughrawlinson', 'ip' => ''),
    array('domain'=> 'https://twitter.com/jna_sh','ip' => ''),
    array('domain'=> 'https://twitter.com/jr0cket','ip' => ''),
    array('domain'=> 'https://twitter.com/JFKingsley','ip' => ''),
    array('domain'=> 'https://twitter.com/man0jn','ip' => ''),
    array('domain'=> 'https://twitter.com/thebeebs','ip' => ''),
    array('domain'=> 'https://twitter.com/martinkearn','ip' => ''),
    array('domain'=> 'https://twitter.com/mseckington','ip' => ''),
    array('domain'=> 'https://twitter.com/picsoung','ip' => ''),
    array('domain'=> 'https://twitter.com/leggetter','ip' => ''),
    array('domain'=> 'https://twitter.com/dn0t','ip' => ''),
    array('domain'=> 'https://twitter.com/rbin','ip' => ''),
    array('domain'=> 'https://twitter.com/rumyra','ip' => ''),
    array('domain'=> 'https://twitter.com/samphippen','ip' => ''),
    array('domain'=> 'https://twitter.com/simon_tabor','ip' => ''),
    array('domain'=> 'https://twitter.com/SeraAndroid','ip' => ''),
    array('domain'=> 'https://twitter.com/pimterry','ip' => ''),
    array('domain'=> 'http://twitter.com/andypiper','ip' => ''),
    array('domain'=> 'https://twitter.com/hazanjon','ip' => ''),
);


function getDB() {
try {
$db = new PDO('mysql:host=localhost;dbname=hackference2015;charset=utf8', 'root', 'root');

    }
catch(PDOException $e)
    {
    echo $e->getMessage();
    }

    return $db;
}

function getData($domain) {
    $endpoint = "http://api.majestic.com/api/json?app_api_key=AA3D7FEDA8E34089FB821FF4A2A97EBA&cmd=GetRefDomains&item0=".$domain."&Count=1000&datasource=fresh&OrderBy1=1";
    $json = file_get_contents($endpoint);
    return $json;
}

function getDomainId($domain, $ip) {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM domains WHERE domain=:domain");

    $stmt->bindParam(':domain', $domain);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        return $row['id'];
    } else {
        $stmt = $db->prepare("INSERT INTO domains (domain, ip) VALUES(:domain, :ip)");

        $stmt->bindParam(':domain', $domain);
        $stmt->bindParam(':ip', $ip);
        $stmt->execute();
        return $db->lastInsertId();;
    }
}

function addRefDomain($domain, $refdomain, $rank, $trust) {
    $db = getDB();
    $stmt = $db->prepare("INSERT INTO refdomains (domainid, refdomainid, trust, rank)
        VALUES(:domain, :refdomain, :trust, :rank)");

    $stmt->bindParam(':domain', $domain);
    $stmt->bindParam(':refdomain', $refdomain);
    $stmt->bindParam(':rank', $rank);
    $stmt->bindParam(':trust', $trust);

    $stmt->execute();
    return $db->lastInsertId();;
}

function processDomains($urls) {

    $return = array();

    for ($i = 0; $i < count($urls); $i++) {
        $url = $urls[$i];

        $domainId = getDomainId($url['domain'], $url['ip']);
       $data = json_decode(getData($url['domain']));
       $results = $data->DataTables->Results->Data;

       for ($x = 0; $x < count($results); $x++) {
        $result = $results[$x];
        $domain = $result->Domain;
        $rank = $result->AlexaRank;
        $ip = $result->IP;
        $trust = $result->TrustFlow;

        $refId = getDomainId($domain, $ip);
        addRefDomain($domainId, $refId, $rank, $trust);

        $return[] =  array('domain'=> $domain, 'ip'=>$ip);
       }


    }

    return $return;
}



function calculateInfluence($id, &$totalRank, &$totalTrust, &$totalRef) {
    $db = getDB();

    $stmt = $db->prepare("SELECT * FROM refdomains AS ref INNER JOIN domains AS d ON ref.domainid=:domainid AND d.id=ref.refdomainid");
    $stmt->bindParam(':domainid', $id);
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($rows as $row) {
        if ($row['rank'] != -1) {
            $totalRank += $row['rank'];
        }
        $totalTrust += $row['trust'];
    }

    $totalRef += count($rows);

    return $rows;
}

function processDomainInfluences($domain) {
    $db = getDB();

    //domain to id
    $stmt = $db->prepare("SELECT * FROM domains WHERE domain=:domain");
    $stmt->bindParam(':domain', $domain);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

     $id = $row['id'];

     $trust = 0;
     $rank = 0;
     $totalRef = 0;

    //First degree influence
    $rows = calculateInfluence($id, $rank, $trust, $totalRef);
    //Second degree influence
    foreach ($rows as $row) {
        calculateInfluence($row['refdomainid'], $rank, $trust, $totalRef);
    }

    $stmt = $db->prepare("INSERT INTO influences (domainid, trust, rank, totalRef)
        VALUES(:domain, :trust, :rank, :totalRef)");

    $stmt->bindParam(':domain', $domain);
    $stmt->bindParam(':rank', $rank);
    $stmt->bindParam(':trust', $trust);
    $stmt->bindParam(':totalRef', $totalRef);

    $stmt->execute();
}

// $result = processDomains($domains);
// $second = processDomains($result);


// echo addDomain('aston.ac.uk', '123');

// $json = file_get_contents($endpoint);
// echo $json;


// foreach ($domains as $domain) {
//     processDomainInfluences($domain['domain']);
// }

function processIps() {
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM domains WHERE ip > ''");
    $stmt->execute();
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $reader = new Reader('GeoLite2-City.mmdb');


    foreach ($rows as $row) {

        try {
            $record = $reader->city($row['ip']);

            $stmt = $db->prepare("INSERT INTO locations (domainid, lat, lng)
            VALUES(:domain, :lat, :lng)");

            $stmt->bindParam(':domain', $row['id']);
            $lat = $record->location->latitude;
            $lng = $record->location->longitude;
            $stmt->bindParam(':lat', $lat);
            $stmt->bindParam(':lng', $lng);

            $stmt->execute();
        } catch (Exception $ex) {

        }

    }
}


$time_elapsed_secs = microtime(true) - $start;

echo "\nFinished - Elapsed: ".$time_elapsed_secs;

?>



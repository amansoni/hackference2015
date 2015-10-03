<?php

header('Content-Type: application/json');

require_once 'majesticseo-external-rpc/APIService.php';


$endpoint = "http://developer.majesticseo.com/api_command";


$url = 'www.aston.ac.uk';

$parameters = array();
$parameters["items"] = 1;
$parameters["item0"] = 'www.aston.ac.uk';
$parameters["datasource"] = "fresh";

$api_service = new APIService('AA3D7FEDA8E34089FB821FF4A2A97EBA', $endpoint);
$response = $api_service->executeCommand("GetRefDomains", $parameters);

if($response->isOK() == "true") {

    $refDomains = array();

    $results = $response->getTableForName("Results");

    foreach($results->getTableRows() as $row) {
        $refDomain = new stdClass();
        $refDomain->url =  $row['Domain'];
        $refDomain->ip =  $row['IP'];
        $refDomains[] = $refDomain;
    }
    echo json_encode($refDomains);

} else {
    echo 'Error Message';
    echo $response->getErrorMessage();
}

?>

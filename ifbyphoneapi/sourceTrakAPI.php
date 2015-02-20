<?php

//This file is called by the AJAX POST from the website and responds with JSON data
//Make sure to add your API key below


require 'sourceTrakAPIclass.php';
header('Content-type: application/json');

// Get the variables from the AJAX application
foreach ($_GET as $key => $value)
{
     $url .= "&$key=$value";
}

$sourcetrakClass = new sourceTrakAPI();

$api_key = 'API KEY GOES HERE';

$set = $_GET['set_id'];
$referer = $_GET['referer'];
$baseURI = $_GET['baseURI'];


if (isset($_GET['_ibp_unique_id'])) {
    $_ibp_unique_id = $_GET['_ibp_unique_id'];
} else {
    $_ibp_unique_id = '';
}


if (isset($_GET['log_id'])) {
    $log_id = $_GET['log_id'];
} else {
    $log_id = "";
}

//Make api call
$sourcetrakNumber = $sourcetrakClass->SourceTrak($set, $_ibp_unique_id, $log_id, $api_key, $referer, $baseURI);


echo '{"root":';
$jsonArray = array(
    "logXML" => "$sourcetrakNumber[log_id]",
    "phone"  => "$sourcetrakNumber[number]",
);

echo json_encode($jsonArray);
echo '}';

?>
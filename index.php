<?php
header('Access-Control-Allow-Origin: *');
header("Cache-Control: no-cache, must-revalidate\n");
header("Content-Type: application/json\n\n");

define('APP_ROOT', dirname(__FILE__));
define('PSI_INTERNAL_XML', true);

require_once APP_ROOT.'/includes/autoloader.inc.php';

$output = new WebpageXML(false, null);
if (defined('PSI_JSON_ISSUE') && (PSI_JSON_ISSUE)) {
    $json = json_encode(simplexml_load_string(str_replace(">", ">\n", $output->getXMLString()))); // solving json_encode issue
} else {
    $json = json_encode(simplexml_load_string($output->getXMLString()));
}

$data = json_decode($json, true);
$endpoint =  '/' . $data['Vitals']['@attributes']['Hostname'] . 
             '/' . $data['Generation']['@attributes']['timestamp'] . '.json';

$ch = curl_init(PSI_PLUGIN_FIREBASE_ENDPOINT . $endpoint);

curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
curl_setopt($ch, CURLOPT_POSTFIELDS, $json);

$response = curl_exec($ch);
var_dump($response);
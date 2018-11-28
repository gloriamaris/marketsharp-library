<?php
// Display errors
error_reporting(E_ALL);
ini_set('display_errors', 'On');

require_once($_SERVER['DOCUMENT_ROOT'] . '/marketsharp-library/index.php');
require_once(MARKETSHARP_DIRECTORY . 'src/MarketSharpApi.class.php');

$marketSharp = new MarketSharpApi(TRUE);    // has credentials in env file

$marketSharp->setApiUrl();
$marketSharp->setEnv();

$postData = [
    'firstName' => 'Guthrie',
    'lastName' => 'Govan',
    'email' => 'guthriegovan@aristocrats.com',
    'address1' => '13 Sea Road',
    'city' => 'Garden City',
    'state' => 'NY',
    'zip' => '11530',
    'cellPhone' => '6318587738',
    'workPhone' => '5513457896',
    'homePhone' => '9753154136',
    'besttime' => '2018-12-01 13:00'
];

$result = $marketSharp->sendLead($postData);

echo "<pre>";
print_r($result);
echo "</pre>";

?>

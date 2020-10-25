<?php

require_once 'vendor/autoload.php';
$helloServiceUrl = getenv('HELLO_SERVICE_URL');
$worldServiceUrl = getenv('WORLD_SERVICE_URL');

$client = new \GuzzleHttp\Client();

$promises['helloService'] =  $client->sendAsync(new \GuzzleHttp\Psr7\Request(
        'GET', 
        $helloServiceUrl, 
        [
           'Accept' => 'application/json'
        ]
));

$promises['worldService'] =  $client->sendAsync(new \GuzzleHttp\Psr7\Request(
        'GET', 
        $worldServiceUrl, 
        [
          'Accept' => 'application/json'
        ]
));

$responses = \GuzzleHttp\Promise\unwrap($promises);

$hello = json_decode($responses['helloService']->getBody(), true);
$world = json_decode($responses['worldService']->getBody(), true);

header('Content-Type: application/json; charset=utf-8');
echo json_encode(['message' => $hello['message'].' '.$world['message']]);
return;



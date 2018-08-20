<?php
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/credentials.php';

$redirectUrl = 'https://teamleader-php-sdk.dev/examples/add-company.php';

$connection = new \Teamleader\Connection();
$connection->setClientId( $clientId );
$connection->setClientSecret( $clientSecret );
$connection->setRedirectUrl( $redirectUrl );
$connection->connect();

$client = new \Teamleader\Client( $connection );

$company = $client->company( [
    'name' => 'Test API v2',
] )->save();

var_dump($company);
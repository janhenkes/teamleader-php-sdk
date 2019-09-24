<?php
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/credentials.php';

$redirectUrl = 'https://teamleader-php-sdk.test/examples/acquire-access-token.php';

$connection = new \Teamleader\Connection();
$connection->setClientId( $clientId );
$connection->setClientSecret( $clientSecret );
$connection->setRedirectUrl( $redirectUrl );

$connection->acquireAccessToken();

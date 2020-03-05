<?php
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/credentials.php';

$connection = new \Teamleader\Connection();
$connection->setClientId( $clientId );
$connection->setClientSecret( $clientSecret );

$client = new \Teamleader\Client( $connection );

$product = $client->product(
    [
        'name' => 'cookies',
        'code'  => 'COOK-DARKCHOC-42',
        'purchase_price' => [
            'amount' => 123.3,
            'currency' => 'EUR',
        ],
        'selling_price' => [
            'amount' => 123.3,
            'currency' => 'EUR',
        ],
    ]
);

$product->save();

var_dump( $product );

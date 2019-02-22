<?php
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/credentials.php';

$connection = new \Teamleader\Connection();
$connection->setClientId( $clientId );
$connection->setClientSecret( $clientSecret );

$client = new \Teamleader\Client( $connection );

$contact = $client->contact(
    [
        'first_name' => 'Chuck',
        'last_name'  => 'Norris',
    ]
);

$contact->save();

var_dump( $contact );

$removeContact = $client->contact(
    [
        'id' => $contact->id,
    ]
);

$removeContact->remove();

var_dump( 'Contact succesfully removed' );
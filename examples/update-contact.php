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

$Updatecontact = $client->contact(
    [
        'id' => $contact->id,
        'first_name' => 'Chuck update',
        'last_name'  => 'Norris update',
    ]
);

$Updatecontact->save();

var_dump( 'Contact has been succesfully updated' );
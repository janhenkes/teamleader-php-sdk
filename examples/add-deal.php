<?php
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/credentials.php';

$connection = new \Teamleader\Connection();
$connection->setClientId( $clientId );
$connection->setClientSecret( $clientSecret );

$client = new \Teamleader\Client( $connection );

$company = $client->company( [
    'name' => 'Test API v2',
] )->save();

var_dump( $company );

$contact = $client->contact( [
    'first_name' => 'John',
    'last_name'  => 'Doe',
] )->save();

var_dump( $contact );

$deal = $client->deal( [
    'lead'     => [
        'customer'          => [
            'type' => 'company',
            'id'   => $company->id,
        ],
        'contact_person_id' => $contact->id,
    ],
    'title'    => 'Test deal API v2',
    'phase_id' => '1bda9497-dd18-0790-ad53-9be18353818d',
] )->save();

var_dump( $deal );
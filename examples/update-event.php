<?php
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/credentials.php';

$connection = new \Teamleader\Connection();
$connection->setClientId( $clientId );
$connection->setClientSecret( $clientSecret );

$client = new \Teamleader\Client( $connection );

$event = $client->event(
    [
        'title' => 'Test event 1',
        'description' => '',
        'activity_type_id' => 'a1ad9b89-fe90-00fe-a51a-40bcd8ed85a0',
        'starts_at' => '2019-02-28T16:00:00+00:00',
        'ends_at' => '2019-02-28T16:20:00+00:00',
    ]
);

$event->save();

var_dump( $event );

$updateEvent = $client->event(
    [
        'id' => $event->id,
        'title' => 'Test event updated',
        'description' => 'This is the updated version of the event',
        'activity_type_id' => 'a1ad9b89-fe90-00fe-a51a-40bcd8ed85a0',
        'starts_at' => '2019-02-28T16:00:00+00:00',
        'ends_at' => '2019-02-28T16:20:00+00:00',
    ]
);

$updateEvent->save();

var_dump( 'Event has been succesfully updated' );
<?php
require __DIR__ . '/../vendor/autoload.php';

require __DIR__ . '/credentials.php';

$connection = new \Teamleader\Connection();
$connection->setClientId( $clientId );
$connection->setClientSecret( $clientSecret );

$client = new \Teamleader\Client( $connection );

$companyData = [
    'name' => 'TEST Jannes & Mannes',
    'business_type' => NULL,
    'vat_number' => 'BE 0899.623.035',
    'national_identification_number' => NULL,
    'emails' =>
        [
            0 =>
                [
                    'type' => 'primary',
                    'email' => 'info@teamleader.eu',
                ],
        ],
    'telephones' =>
        [
            0 =>
                [
                    'type' => 'phone',
                    'number' => '+32 9 298 06 32',
                ],
        ],
    'website' => 'http://www.teamleader.eu',
    'iban' => NULL,
    'bic' => NULL,
    'language' => 'nl',
    'payment_term' => NULL,
    'preferred_currency' => NULL,
    'invoicing_preferences' =>
        [
            'electronic_invoicing_address' => NULL,
        ],
    'added_at' => '2019-02-15T07:27:54+00:00',
    'updated_at' => '2019-02-15T07:27:54+00:00',
    'web_url' => 'https://app.teamleader.eu/company_detail.php?id=e9fb81ca-983f-0bca-bc71-0963618dbae8',
    'primary_address' =>
        [
            'line_1' => 'Dok Noord 3A/101',
            'postal_code' => '9000',
            'city' => 'Gent',
            'country' => 'BE',
        ],
    'responsible_user' => NULL,
    'tags' =>
        [
            "test-tag-1",
            "test-tag-2",
        ],
];

$company = $client->company( $companyData )->save();

var_dump($company);

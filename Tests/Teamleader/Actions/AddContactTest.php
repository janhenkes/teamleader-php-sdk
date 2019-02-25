<?php

class AddContactTest extends \PHPUnit\Framework\TestCase
{
    private $contactData = [
        'first_name' => 'Jeroen',
        'last_name' => 'De Wit',
        'emails' =>
            [
                0 =>
                    [
                        'type' => 'primary',
                        'email' => 'sales@teamleader.be',
                    ],
            ],
        'salutation' => NULL,
        'telephones' =>
            [
                0 =>
                    [
                        'type' => 'mobile',
                        'number' => '+32 9 298 06 15',
                    ],
            ],
        'website' => NULL,
        'gender' => 'male',
        'birthdate' => NULL,
        'iban' => NULL,
        'bic' => NULL,
        'national_identification_number' => NULL,
        'language' => 'nl',
        'payment_term' => NULL,
        'invoicing_preferences' =>
            [
                'electronic_invoicing_address' => NULL,
            ],
        'added_at' => '2019-02-15T07:27:54+00:00',
        'updated_at' => '2019-02-15T07:27:54+00:00',
        'web_url' => 'https://app.teamleader.eu/contact_detail.php?id=a9601b72-fcf7-07d0-8d79-041c91cf65c1',
        'primary_address' =>
            [
                'line_1' => NULL,
                'postal_code' => NULL,
                'city' => NULL,
                'country' => 'BE',
            ],
        'tags' =>
            [
            ],
    ];

    public function testContactGetsCreated(): void
    {
        $connectionMock = $this->createMock(\Teamleader\Connection::class);
        $connectionMock
            ->expects($this->once())
            ->method('post')
            ->willReturn(
                json_decode(
                    '{
                            "data": {
                            "type": "contact",
                            "id": "7c1d8672-f502-4333-9ea4-7a45add15115"
                            }
                        }',
                    true
                )
            );

        $client = new \Teamleader\Client($connectionMock);
        $client->contact(
            $this->contactData
        )->save();
    }
}
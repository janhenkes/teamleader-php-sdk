<?php

class DealTest extends \PHPUnit\Framework\TestCase
{
    public function testDeal(): void
    {
        $connectionMock = $this->createMock(\Teamleader\Connection::class);
        $connectionMock->method('get')
            ->willReturn(
                [
                    'data' => [
                        0 => [
                            'id' => 'f6871b06-6513-4750-b5e6-ff3503b5a029',
                            'title' => 'Interesting deal',
                            'reference' => '2017/2',
                            'status' => 'won',
                            'lead' => [
                                'customer' => [
                                    'type' => 'company',
                                    'id' => '2659dc4d-444b-4ced-b51c-b87591f604d7',
                                ],
                                'contact_person' => [
                                    'type' => 'contact',
                                    'id' => '74c6769e-815a-4774-87d7-dfab9b1a0abb',
                                ],
                            ],
                            'department' => [
                                'type' => 'department',
                                'id' => '92247818-643e-4f5a-bf87-25cd908e8ad9',
                            ],
                            'estimated_value' => [
                                'amount' => 123.3,
                                'currency' => 'EUR',
                            ],
                            'estimated_closing_date' => '2017-05-09',
                            'estimated_probability' => 0.5,
                            'weighted_value' => [
                                'amount' => 123.3,
                                'currency' => 'EUR',
                            ],
                            'current_phase' => [
                                'type' => 'dealPhase',
                                'id' => '676a6070-f7d3-43a6-bc08-cda0d32c27ab',
                            ],
                            'responsible_user' => [
                                'type' => 'user',
                                'id' => '4e81f3c4-7dca-44cb-b126-6158bec392a2',
                            ],
                            'closed_at' => '2017-05-09T11:31:30+00:00',
                            'source' => [
                                'type' => 'dealSource',
                                'id' => 'aba0ad66-bf59-49fa-b546-45dcbc5e7e6e',
                            ],
                            'phase_history' => [
                                0 => [
                                    'phase' => [
                                        'type' => 'dealPhase',
                                        'id' => 'd5a629f2-7b58-4748-aaca-acf9b6d62404',
                                    ],
                                    'started_at' => '2017-05-01 12:00:00',
                                    'started_by' => [
                                        'type' => 'user',
                                        'id' => '4ec55a8c-2d80-472a-a9c2-5ff5ee7eac6a',
                                    ],
                                ],
                            ],
                            'quotations' => [
                                    0 => [
                                        'id' => 'e2314517-3cab-4aa9-8471-450e73449041',
                                        'type' => 'quotation',
                                    ],
                                ],
                            'created_at' => '2017-05-09T11:25:11+00:00',
                            'updated_at' => '2017-05-09T11:30:58+00:00',
                            'web_url' => 'https://app.teamleader.eu/sale_detail.php?id=f6871b06-6513-4750-b5e6-ff3503b5a029',
                            'custom_fields' => [
                                0 => [
                                    'definition' =>
                                        [
                                            'type' => 'customFieldDefinition',
                                            'id' => 'bf6765de-56eb-40ec-ad14-9096c5dc5fe1',
                                        ],
                                    'value' => '092980616',
                                ],
                            ],
                        ]
                    ]
                ]
            );

        $client = new \Teamleader\Client($connectionMock);
        $deals = $client->deal()->get();

        $this->assertEquals('Interesting deal', $deals[0]->title);
    }
}

<?php

class QuotationTest extends \PHPUnit\Framework\TestCase
{
    public function testEntity(): void
    {
        $connectionMock = $this->createMock(\Teamleader\Connection::class);
        $connectionMock
            ->method('get')
            ->willReturn(
                [
                    'data' =>
                        [
                            0 =>
                                [
                                    'id' => '5b16f6ee-e302-0079-901b-50c26c4a55b1',
                                    'deal' => [
                                        'type' => 'deal',
                                        'id' => '53474a7a-f9b2-4dd4-88a8-40ce773c7a64'
                                    ],
                                    'currency_exchange_rate' => [
                                        'from' => 'USD',
                                        'to' => 'EUR',
                                        'rate' => 1.1234
                                    ],
                                    'total' => [
                                        'tax_exclusive' => [
                                            'amount' => 123.3,
                                            'currency' => 'EUR'
                                        ],
                                        'tax_inclusive' => [
                                            'amount' => 321.1,
                                            'currency' => 'EUR'
                                        ]
                                    ],
                                    'taxes' => [
                                        'rate' => 0.21,
                                        'taxable' => [
                                            'amount' => 123.3,
                                            'currency' => 'EUR'
                                        ],
                                        'tax' => [
                                            'amount' => 123.3,
                                            'currency' => 'EUR'
                                        ] 
                                    ],
                                    'purchase_price' => [
                                        'amount' => 123.3,
                                        'currency' => 'EUR'
                                    ],
                                    "created_at" => "2017-05-09T11:25:11+00:00",
                                    "updated_at" => "2017-05-09T11:30:58+00:00"
                                ]
                        ],
                ]
            );

        $client = new \Teamleader\Client( $connectionMock );

        $quotation = $client->quotation()->get();
        $this->assertEquals('5b16f6ee-e302-0079-901b-50c26c4a55b1', $quotation[0]->id);
        $this->assertEquals('USD', $quotation[0]->currency_exchange_rate['from']);
    }
}

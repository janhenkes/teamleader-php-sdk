<?php

class TaxRateTest extends \PHPUnit\Framework\TestCase
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
                                    'id' => '6d893efb-fb7b-02f5-bf5e-04590cc10605',
                                    'department' =>
                                        [
                                            'type' => 'department',
                                            'id' => '59575e9b-b8e9-0499-835f-a38a7912300f',
                                        ],
                                    'description' => 'Verkoop Intracommunautair',
                                    'rate' => 0.0,
                                ],
                            1 =>
                                [
                                    'id' => 'cb73395f-42ba-0447-8ca7-5a1409ff5fcc',
                                    'department' =>
                                        [
                                            'type' => 'department',
                                            'id' => '59575e9b-b8e9-0499-835f-a38a7912300f',
                                        ],
                                    'description' => 'Verkoop Intracommunautair diensten',
                                    'rate' => 0.0,
                                ],
                        ]
                ]
            );

        $client = new \Teamleader\Client( $connectionMock );

        $taxRates = $client->taxRate()->get();
        $this->assertEquals('Verkoop Intracommunautair', $taxRates[0]->description);
    }
}

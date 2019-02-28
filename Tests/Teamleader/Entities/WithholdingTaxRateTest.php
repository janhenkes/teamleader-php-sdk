<?php

class WithholdingTaxRateTest extends \PHPUnit\Framework\TestCase
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
                                    'id' => 'c93ddb52-0af8-47d9-8551-441435be66a7',
                                    'department' =>
                                        [
                                            'type' => 'department',
                                            'id' => '59575e9b-b8e9-0499-835f-a38a7912300f',
                                        ],
                                    'description' => 'Ritenuta d\'acconto 23% su 20%',
                                    'rate' => 0.046,
                                ],
                        ]
                ]
            );

        $client = new \Teamleader\Client( $connectionMock );

        $withholdingTaxRates = $client->withholdingTaxRate()->get();
        $this->assertEquals('c93ddb52-0af8-47d9-8551-441435be66a7', $withholdingTaxRates[0]->id);
    }
}

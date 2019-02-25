<?php

class DealSourceTest extends \PHPUnit\Framework\TestCase
{
    public function testEntity()
    {
        $connectionMock = $this->createMock(\Teamleader\Connection::class);
        $connectionMock->method('get')
            ->willReturn(
                [
                    'data' =>
                        [
                            0 =>
                                [
                                    'id' => 'b18c298b-0e6f-495d-8fcd-31dfecaea741',
                                    'name' => 'Referral',
                                ],
                        ],
                ]
            );

        $client = new \Teamleader\Client($connectionMock);

        $dealSources = $client->dealSource()->get();

        $this->assertEquals('Referral', $dealSources[0]->name);
    }
}

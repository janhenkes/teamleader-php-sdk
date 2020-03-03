<?php

class WorkTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testContact(): void
    {
        $connectionMock = $this->createMock(\Teamleader\Connection::class);
        $connectionMock->method('get')
            ->willReturn(
                [
                    'data' => [
                        0 =>
                            [
                                'id' => '75b48b46-fc2a-07ed-9c79-46ddc2934b79',
                                'name' => 'Planning'
                            ],
                    ]
                ]
            );

        $client = new \Teamleader\Client($connectionMock);
        $workType = $client->workType()->get();

        $this->assertEquals('Planning', $workType[0]->name);
    }
}

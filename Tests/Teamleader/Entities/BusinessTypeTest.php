<?php

class BusinessTypeTest extends \PHPUnit\Framework\TestCase
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
                                    'id' => '2b87a1dd-6ca2-08a2-b32b-6ef092468ec9',
                                    'name' => 'S.s.',
                                    'country' => 'IT',
                                ],
                        ],
                ]
            );

        $client = new \Teamleader\Client( $connectionMock );

        $businessTypes = $client->businessType()->get();
        $this->assertEquals('S.s.', $businessTypes[0]->name);
    }
}

<?php

class DepartmentTest extends \PHPUnit\Framework\TestCase
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
                                    'id' => '59575e9b-b8e9-0499-835f-a38a7912300f',
                                    'name' => 'MIB',
                                    'currency' => 'EUR',
                                    'vat_number' => NULL,
                                ],
                        ],
                ]
            );

        $client = new \Teamleader\Client( $connectionMock );

        $departments = $client->department()->get();
        $this->assertEquals('MIB', $departments[0]->name);
    }
}

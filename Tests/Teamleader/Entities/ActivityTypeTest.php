<?php

class ActivityTypeTest extends \PHPUnit\Framework\TestCase
{
    public function testEntity(): void
    {
        $connectionMock = $this->createMock(\Teamleader\Connection::class);
        $connectionMock->method('get')
            ->willReturn(
                [
                    'data' =>
                        [
                            0 =>
                                [
                                    'id' => '927de1ae-8927-0132-a418-2ee27664b711',
                                    'name' => 'call',
                                ],
                            1 =>
                                [
                                    'id' => 'a1ad9b89-fe90-00fe-a51a-40bcd8ed85a0',
                                    'name' => 'meeting',
                                ],
                            2 =>
                                [
                                    'id' => '82b73325-4608-063e-9e13-2b8b40812832',
                                    'name' => 'task',
                                ],
                        ],
                ]
            );

        $client = new \Teamleader\Client($connectionMock);

        $activityTypes = $client->activityType()->get();

        $this->assertCount(3, $activityTypes);
        $this->assertEquals('call', $activityTypes[0]->name);
    }
}
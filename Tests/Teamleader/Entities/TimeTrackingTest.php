<?php

class TimeTrackingTest extends \PHPUnit\Framework\TestCase
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
                                'user' => [
                                    'type' => 'user',
                                    'id' => 'a4234bed-2511-0aa7-a459-d139d8e43aec'
                                ],
                                'work_type' => [
                                    'type' => 'workType',
                                    'id' => 'f130a668-720a-051c-ba5b-859a17a47e86'
                                ],
                                'started_on' => '2020-02-18',
                                'started_at' => '2020-02-18T07:55:32+00:00',
                                'ended_at' => '2020-02-18T08:56:32+00:00',
                                'duration' => 3660,
                                'description' => 'Lorem ipsum',
                                'subject' => [
                                    'type' => 'todo',
                                    'id' => '7ec8dbbf-eaed-0a60-8163-c03456da8564'
                                ],
                                'invoiceable' => true
                            ],
                    ]
                ]
            );

        $client = new \Teamleader\Client($connectionMock);
        $timeTracking = $client->timeTracking()->get();

        $this->assertEquals('Lorem ipsum', $timeTracking[0]->description);
    }
}

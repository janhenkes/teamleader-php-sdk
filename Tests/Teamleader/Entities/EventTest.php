<?php

class EventTest extends \PHPUnit\Framework\TestCase
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
                                    'id' => '8169e4ea-02c8-0654-a76f-3320b351680c',
                                    'creator' =>
                                        [
                                            'type' => 'user',
                                            'id' => '5db433c9-8c2f-0397-ba58-c1de8ed39de3',
                                        ],
                                    'todo' => NULL,
                                    'activity_type' =>
                                        [
                                            'type' => 'activityType',
                                            'id' => 'a1ad9b89-fe90-00fe-a51a-40bcd8ed85a0',
                                        ],
                                    'title' => 'Test event',
                                    'description' => 'This is the best event ever',
                                    'starts_at' => '2019-02-28T17:00:00+01:00',
                                    'ends_at' => '2019-02-28T17:20:00+01:00',
                                    'location' => NULL,
                                    'links' =>
                                        [
                                        ],
                                    'attendees' =>
                                        [
                                            0 =>
                                                [
                                                    'type' => 'user',
                                                    'id' => '5db433c9-8c2f-0397-ba58-c1de8ed39de3',
                                                ],
                                        ],
                                ],
                    ]
                ]
            );

        $client = new \Teamleader\Client( $connectionMock );

        $events = $client->event()->get();
        $this->assertEquals('Test event', $events[0]->title);
        $this->assertEquals('8169e4ea-02c8-0654-a76f-3320b351680c', $events[0]->id);
    }
}
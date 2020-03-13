<?php

class TaskTest extends \PHPUnit\Framework\TestCase
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
                                'id' => '87e43844-610e-0010-9862-def5b5d1517b',
                                'description' => 'Random description goes here',
                                'completed' => true,
                                'completed_at' => '2020-01-14T08:33:41+01:00',
                                'due_on' => '2020-01-10',
                                'estimated_duration' => [
                                    'unit' => 'min',
                                    'value' => 60
                                ],
                                'work_type' => [
                                    'type' => 'workType',
                                    'id' => '6cf22667-bbe3-038a-a15f-1cf494e47e83'
                                ],
                                'customer' => null,
                                'milestone' => null,
                                'deal' => null,
                                'assignee' => [
                                    'type' => 'user',
                                    'id' => 'a313960e-2b33-09f7-9e51-aa8c2e544727'
                                ],
                                'project' => null
                            ],
                    ]
                ]
            );

        $client = new \Teamleader\Client($connectionMock);
        $tasks = $client->task()->get();

        $this->assertEquals('Random description goes here', $tasks[0]->description);
    }
}

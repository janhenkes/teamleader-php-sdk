<?php

class MilestoneTest extends \PHPUnit\Framework\TestCase
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
                                'id' => 'aedd70ae-c14a-0248-b76b-b780e43cd6f4',
                                'name' => 'Project Zero Phase 1',
                                'due_on' => '2020-01-09',
                                'status' => 'closed',
                                'project' => [
                                    'type' => 'project',
                                    'id' => '70000e7d-c4fd-0283-916a-4ef3c213fb6b'
                                ],
                                'responsible_user' => [
                                    'type' => 'user',
                                    'id' => '64f72320-fab0-078f-8854-2493f1d43aeb'
                                ],
                                'invoicing_method' => 'time_and_materials',
                                'actuals' => [
                                    'billable_amount' => [
                                        'amount' => 0,
                                        'currency' => 'EUR'
                                    ],
                                    'costs' => [
                                        'amount' => 0,
                                        'currency' => 'EUR'
                                    ],
                                    'result' => [
                                        'amount' => 0,
                                        'currency' => 'EUR'
                                    ]
                                ],
                                'budget' => [
                                    'provided' => [
                                        'amount' => 0,
                                        'currency' => 'EUR'
                                    ],
                                    'spent' => [
                                        'amount' => 0,
                                        'currency' => 'EUR'
                                    ],
                                    'remaining' => [
                                        'amount' => 0,
                                        'currency' => 'EUR'
                                    ],
                                    'allocated' => [
                                        'amount' => 0,
                                        'currency' => 'EUR'
                                    ],
                                    'projected' => [
                                        'amount' => 0,
                                        'currency' => 'EUR'
                                    ]
                                ],
                                'allocated_time' => [
                                    'unit' => 's',
                                    'value' => 0
                                ]
                            ],
                    ]
                ]
            );

        $client = new \Teamleader\Client($connectionMock);
        $milestones = $client->milestone()->get();

        $this->assertEquals('Project Zero Phase 1', $milestones[0]->name);
    }
}

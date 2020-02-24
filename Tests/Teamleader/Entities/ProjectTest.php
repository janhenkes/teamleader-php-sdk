<?php

class ProjectTest extends \PHPUnit\Framework\TestCase
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
                                'id' => 'ccd97866-c28b-01e6-aa6f-c1867a13fb8f',
                                'reference' => '9',
                                'title' => 'Project Zero',
                                'description' => null,
                                'status' => 'active',
                                'starts_on' => '2020-01-08',
                                'due_on' => '2020-01-15',
                                'customer' => [
                                    'type' => 'company',
                                    'id' => '500ef52d-799a-0270-8e72-a691f1f16d2b'
                                ],
                                'source' => null,
                                'actuals' => [
                                    'costs' => [
                                        'amount' => 0,
                                        'currency' => 'EUR'
                                    ],
                                    'result' => [
                                        'amount' => 0,
                                        'currency' => 'EUR'
                                    ],
                                    'profit_percentage' => 0,
                                    'billable_amount' => [
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
                                        'total' => [
                                            'amount' => 0,
                                            'currency' => 'EUR'
                                        ],
                                        'time' => [
                                            'amount' => 0,
                                            'currency' => 'EUR'
                                        ],
                                        'materials' => [
                                            'amount' => 0,
                                            'currency' => 'EUR'
                                        ]
                                    ],
                                    'remaining' => [
                                        'amount' => 0,
                                        'currency' => 'EUR'
                                    ],
                                    'allocated' => [
                                        'amount' => 0,
                                        'currency' => 'EUR'
                                    ],
                                    'forecasted' => [
                                        'amount' => 0,
                                        'currency' => 'EUR'
                                    ]
                                ]
                            ],
                    ]
                ]
            );

        $client = new \Teamleader\Client($connectionMock);
        $projects = $client->project()->get();

        $this->assertEquals('Project Zero', $projects[0]->title);
    }
}

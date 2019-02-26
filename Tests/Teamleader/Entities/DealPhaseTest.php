<?php

class DealPhaseTest extends \PHPUnit\Framework\TestCase
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
                                    'id' => '5e8d5a5f-37ee-0814-a55d-e7b2b5787139',
                                    'name' => 'Nieuw',
                                ],
                            1 =>
                                [
                                    'id' => '10760d44-1b03-0b3f-8f59-bf81b7e8713a',
                                    'name' => 'Gecontacteerd',
                                ],
                            2 =>
                                [
                                    'id' => 'af97a4b7-d53d-0ca3-be58-8edc7648713b',
                                    'name' => 'Meeting gepland',
                                ],
                            3 =>
                                [
                                    'id' => 'ab36ad02-7a1c-0762-bd5e-eeb6b5b8713c',
                                    'name' => 'Offerte verzonden',
                                ],
                            4 =>
                                [
                                    'id' => 'cf0f1a39-c07d-0c4f-8153-3ae6f8a8713d',
                                    'name' => 'Aanvaard',
                                ],
                            5 =>
                                [
                                    'id' => 'db0a815c-6b7e-0369-af55-0c25a428713e',
                                    'name' => 'Geweigerd',
                                ]
                        ],
                ]
            );

        $client = new \Teamleader\Client($connectionMock);

        $dealPhases = $client->dealPhase()->get();

        $this->assertCount(6, $dealPhases);
        $this->assertEquals('5e8d5a5f-37ee-0814-a55d-e7b2b5787139', $dealPhases[0]->id);
        $this->assertEquals('Nieuw', $dealPhases[0]->name);
        $this->assertEquals('Geweigerd', $dealPhases[5]->name);
    }
}

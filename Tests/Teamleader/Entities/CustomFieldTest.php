<?php

class CustomFieldTest extends \PHPUnit\Framework\TestCase
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
                                    'id' => 'e3f8d34f-3bdd-039c-a55a-5ef33b04fb66',
                                    'context' => 'sale',
                                    'type' => 'single_line',
                                    'label' => 'OrderReference',
                                ],
                        ],
                ]
            );

        $client = new \Teamleader\Client( $connectionMock );

        $customFields = $client->customFields()->get();
        $this->assertEquals('sale', $customFields[0]->context);
    }
}

<?php

class TagTest extends \PHPUnit\Framework\TestCase
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
                                    'tag' => 'klant',
                                ],
                            1 =>
                                [
                                    'tag' => 'leverancier',
                                ],
                        ],
                ]
            );

        $client = new \Teamleader\Client( $connectionMock );

        $tags = $client->tag()->get();
        $this->assertEquals('leverancier', $tags[1]->tag);
    }
}
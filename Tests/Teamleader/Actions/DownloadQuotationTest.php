<?php

class DownloadQuotationTest extends \PHPUnit\Framework\TestCase
{
    private $invoiceData = [
        'id' => 'e4bf74ca-e900-471f-84b1-276e5d3afae4',
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
    ];

    private $format = "pdf";

    public function testDownloadInvoice(): void
    {
        $connectionMock = $this->createMock(\Teamleader\Connection::class);
        $connectionMock
            ->expects($this->once())
            ->method('post')
            ->willReturn(200);

        $client = new \Teamleader\Client($connectionMock);
        $client->quotation($this->invoiceData)->download($this->format);
    }
}

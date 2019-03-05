<?php

class PaymentTermTest extends \PHPUnit\Framework\TestCase
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
                                    'id' => '65d6698d-3eef-0bf4-b06d-9fcf7f232bc0',
                                    'type' => 'cash',
                                    'days' => 0,
                                ],
                            1 =>
                                [
                                    'id' => 'aa3c6df9-2f1c-0c09-9a68-7658f8232bc1',
                                    'type' => 'after_invoice_date',
                                    'days' => 14,
                                ],
                        ],
                ]
            );

        $client = new \Teamleader\Client( $connectionMock );

        $paymentTerm = $client->paymentTerm()->get();
        $this->assertEquals('65d6698d-3eef-0bf4-b06d-9fcf7f232bc0', $paymentTerm[0]->id);
    }
}

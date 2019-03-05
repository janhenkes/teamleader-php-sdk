<?php

class CreditNoteTest extends \PHPUnit\Framework\TestCase
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
                                    'id' => '41d0b5e0-6596-0d12-8e5f-af798582ebcb',
                                    'department' =>
                                        [
                                            'type' => 'department',
                                            'id' => '59575e9b-b8e9-0499-835f-a38a7912300f',
                                        ],
                                    'credit_note_number' => '1',
                                    'credit_note_date' => '2019-02-25',
                                    'status' => 'booked',
                                    'invoice' => NULL,
                                    'paid' => false,
                                    'paid_at' => NULL,
                                    'invoicee' =>
                                        [
                                            'name' => 'Chuck Norris',
                                            'vat_number' => NULL,
                                            'customer' =>
                                                [
                                                    'type' => 'contact',
                                                    'id' => 'e869348d-9314-0c53-a978-fe6e31d2297b',
                                                ],
                                        ],
                                    'total' =>
                                        [
                                            'tax_exclusive' =>
                                                [
                                                    'amount' => 0.0,
                                                    'currency' => 'EUR',
                                                ],
                                            'tax_inclusive' =>
                                                [
                                                    'amount' => 0.0,
                                                    'currency' => 'EUR',
                                                ],
                                            'payable' =>
                                                [
                                                    'amount' => 0.0,
                                                    'currency' => 'EUR',
                                                ],
                                            'taxes' =>
                                                [
                                                    0 =>
                                                        [
                                                            'rate' => 0.21,
                                                            'taxable' =>
                                                                [
                                                                    'amount' => 0.0,
                                                                    'currency' => 'EUR',
                                                                ],
                                                            'tax' =>
                                                                [
                                                                    'amount' => 0.0,
                                                                    'currency' => 'EUR',
                                                                ],
                                                        ],
                                                ],
                                        ],
                                    'created_at' => '2019-02-25T16:43:42+00:00',
                                    'updated_at' => '2019-02-25T16:43:42+00:00',
                                ],
                        ],
                ]
            );

        $client = new \Teamleader\Client( $connectionMock );

        $creditNotes = $client->creditNote()->get();
        $this->assertEquals('booked', $creditNotes[0]->status);
        $this->assertEquals('Chuck Norris', $creditNotes[0]->invoicee['name']);
    }
}

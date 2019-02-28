<?php

class InvoiceTest extends \PHPUnit\Framework\TestCase
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
                                'id' => '31853d43-1287-09ad-a86b-7098d64b6788',
                                'department' =>
                                    [
                                        'type' => 'department',
                                        'id' => '59575e9b-b8e9-0499-835f-a38a7912300f',
                                    ],
                                'invoice_number' => NULL,
                                'invoice_date' => '2019-02-26',
                                'status' => 'draft',
                                'paid' => false,
                                'paid_at' => NULL,
                                'due_on' => NULL,
                                'invoicee' =>
                                    [
                                        'name' => 'Chuck 5 Norris 5',
                                        'vat_number' => NULL,
                                        'customer' =>
                                            [
                                                'id' => 'e869348d-9314-0c53-a978-fe6e31d2297b',
                                                'type' => 'contact',
                                            ],
                                        'for_attention_of' =>
                                            [
                                                'name' => 'Finance Dept.',
                                                'contact' => NULL,
                                            ],
                                    ],
                                'total' =>
                                    [
                                        'tax_exclusive' =>
                                            [
                                                'amount' => 4995.0,
                                                'currency' => 'EUR',
                                            ],
                                        'tax_inclusive' =>
                                            [
                                                'amount' => 6043.95,
                                                'currency' => 'EUR',
                                            ],
                                        'payable' =>
                                            [
                                                'amount' => 6043.95,
                                                'currency' => 'EUR',
                                            ],
                                        'taxes' =>
                                            [
                                                0 =>
                                                    [
                                                        'rate' => 0.21,
                                                        'taxable' =>
                                                            [
                                                                'amount' => 4995.0,
                                                                'currency' => 'EUR',
                                                            ],
                                                        'tax' =>
                                                            [
                                                                'amount' => 1048.95,
                                                                'currency' => 'EUR',
                                                            ],
                                                    ],
                                            ],
                                        'due' =>
                                            [
                                                'amount' => 6043.95,
                                                'currency' => 'EUR',
                                            ],
                                    ],
                                'created_at' => '2019-02-26T09:18:17+00:00',
                                'updated_at' => '2019-02-26T09:18:17+00:00',
                            ],
                    ]

                ]
            );

        $client = new \Teamleader\Client($connectionMock);

        $invoices = $client->invoice()->get();
        $this->assertEquals('31853d43-1287-09ad-a86b-7098d64b6788', $invoices[0]->id);
    }
}

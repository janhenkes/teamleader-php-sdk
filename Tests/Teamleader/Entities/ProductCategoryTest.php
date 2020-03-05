<?php

class ProductCategoryTest extends \PHPUnit\Framework\TestCase
{
    public function testProductCategory(): void
    {
        $connectionMock = $this->createMock(\Teamleader\Connection::class);
        $connectionMock->method('get')
            ->willReturn(
                [
                    'data' => [
                        [
                            'id'      => '2aa4a6a9-9ce8-4851-a9b3-26aea2ea14c4',
                            'name'    => 'Asian Flowers',
                            'ledgers' => [
                                [
                                    'department' => [
                                        'type' => 'department',
                                        'id'   => '2aa4a6a9-9ce8-4851-a9b3-26aea2ea14c6',
                                    ],
                                    'ledger_account_number' => '70100',
                                ],
                            ],
                        ],
                    ],
                ]
            );

        $client   = new \Teamleader\Client($connectionMock);
        $productcategories = $client->productCategory()->get();

        $this->assertEquals('Asian Flowers', $productcategories[0]->name);
    }
}

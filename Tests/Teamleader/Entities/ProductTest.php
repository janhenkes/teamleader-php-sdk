<?php

class ProductTest extends \PHPUnit\Framework\TestCase
{
    public function testProduct(): void
    {
        $connectionMock = $this->createMock(\Teamleader\Connection::class);
        $connectionMock->method('get')
            ->willReturn(
                [
                    'data' => [
                        [
                            'id'          => 'f8ae61ec-62f3-0538-b028-185c4a5f217f',
                            'name'        => 'cookies',
                            'description' => 'dark chocolate',
                            'code'        => 'COOK-DARKCHOC-42',
                            'purchase_price' => [
                                'amount'   => 123.3,
                                'currency' => 'EUR',
                            ],
                            'selling_price' => [
                                'amount'   => 123.3,
                                'currency' => 'EUR',
                            ]
                        ],
                    ],
                ]
            );

        $client   = new \Teamleader\Client($connectionMock);
        $products = $client->product()->get();

        $this->assertEquals('cookies', $products[0]->name);
    }
}

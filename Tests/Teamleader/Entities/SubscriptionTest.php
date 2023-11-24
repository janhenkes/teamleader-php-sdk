<?php

class SubscriptionTest extends \PHPUnit\Framework\TestCase
{
    public function testEntity(): void
    {
        $connectionMock = $this->createMock(\Teamleader\Connection::class);
        $connectionMock
            ->method('get')
            ->willReturn(
                [
                    'data' => [
                        0 => [
                            "id" => "e2314517-3cab-4aa9-8471-450e73449041",
                            "title" => "Subscription for cookies",
                            "remarks" => "Some more **information** about this subscription",
                            "status" => "active",
                            "department" => [
                                "type" => "department",
                                "id" => "5e90eb0a-b502-4344-aa0f-3b8525af6186"
                            ],
                            "invoicee" => [
                                "customer" => [
                                    "type" => "contact",
                                    "id" => "f29abf48-337d-44b4-aad4-585f5277a456"
                                ],
                                "for_attention_of" => [
                                    "contact" => [
                                        "type" => "contact",
                                        "id" => "d4391f46-a32c-458c-b2ee-833fd27a348d"
                                    ],
                                    "name" => "Radja Nainggolan"
                                ],
                            ],
                            "project" => [
                                "type" => "project",
                                "id" => "2659dc4d-444b-4ced-b51c-b87591f604d7"
                            ],
                            "next_renewal_date" => "2022-06-21",
                            "periodicity" => "monthly",
                            "total" => [
                                "tax_exclusive" => [
                                    "amount" => 123.3,
                                    "currency" => "EUR"
                                ],
                                "tax_inclusive" => [
                                    "amount" => 123.3,
                                    "currency" => "EUR"
                                ],
                                "taxes" => [
                                    0 => [
                                        "rate" => 0.21,
                                        "taxable" => [
                                            "amount" => 123.3,
                                            "currency" => "EUR"
                                        ],
                                        "tax" => [
                                            "amount" => 123.3,
                                            "currency" => "EUR"
                                        ],
                                    ],
                                ],
                            ],
                            "web_url" => "https://focus.teamleader.eu/subscription_detail.php?id=e2314517-3cab-4aa9-8471-450e73449041"
                        ],
                    ],
                ],
            );

        $client = new \Teamleader\Client( $connectionMock );

        $subscription = $client->subscription()->get();
        $this->assertEquals('active', $subscription[0]->status);
        $this->assertEquals('2022-06-21', $subscription[0]->next_renewal_date);
    }
}

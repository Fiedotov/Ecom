<?php

namespace Tests\Feature;

use App\Models\PaymentSubmission;
use App\Models\Property;
use App\Salesforce\Api\Client;
use GuzzleHttp\Psr7\MessageTrait;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\Client\Response;
use Mockery\MockInterface;
use Omnipay\AuthorizeNet\AIMGateway;
use stdClass;
use Tests\TestCase;

class SubmitTransactionTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    public function it_submits_a_transaction(): void
    {
        $this->markTestSkipped('Need to fix mocking');
        $this->withoutExceptionHandling();
        $this->mockGateway();

        $property = Property::factory()->create(['apn' => 'abc123']);

        $response = $this->postJson(route('transactions.submit', ['apn' => $property->apn]), [
            'customer' => [
                'first_name' => 'Hank',
                'last_name' => 'Shank',
                'address' => '123 Fake Street',
                'city' => 'Anytown',
                'state' => 'CA',
                'postal_code' => '90210',
                'country' => 'US',
                'phone' => '555-555-1234',
                'email' => 'user@example.com',
                'full_legal_name' => 'Alpha Beta Gamma',
                'tshirt_size' => 'L',
                'referrer' => 'Uncle Bob',
            ],
            'payments' => 1,
        ]);

        $response->assertSuccessful();
        $this->assertDatabaseCount(PaymentSubmission::class, 1);
    }

    private function mockGateway(): void
    {
        $this->mock(
            AIMGateway::class,
            fn(MockInterface $mock) => $mock->shouldReceive('purchase')
                ->once()
                ->andReturn(
                    new class {
                        public function send()
                        {
                            return new class {
                                public function getMessage(): string
                                {
                                    return '';
                                }

                                public function isSuccessful(): bool
                                {
                                    return true;
                                }

                                public function getData(): stdClass
                                {
                                    return (object)[
                                        "messages" =>  [
                                            "resultCode" =>  "Ok",
                                            "message" =>  [
                                                "code" =>  "I00001",
                                                "text" =>  "Successful."
                                            ]
                                        ],
                                        "transactionResponse" =>  [
                                            "responseCode" =>  "1",
                                            "authCode" =>  "7PDR9D",
                                            "avsResultCode" =>  "Y",
                                            "cvvResultCode" =>  "P",
                                            "cavvResultCode" =>  "2",
                                            "transId" =>  "40115393094",
                                            "refTransID" =>  [],
                                            "transHash" =>  [],
                                            "testRequest" =>  "0",
                                            "accountNumber" =>  "XXXX4242",
                                            "accountType" =>  "Visa",
                                            "messages" =>  [
                                                "message" =>  [
                                                    "code" =>  "1",
                                                    "description" =>  "This transaction has been approved."
                                                ]
                                            ],
                                            "transHashSha2" =>  [],
                                            "networkTransId" =>  "TZQVNJ4URYJCO29D1VJGZ4C"
                                        ]
                                    ];
                                }
                            };
                        }
                    }
                )
        );

        $this->mock(
            Client::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('storeAccount')->once()->andReturn(new Response(new class {use MessageTrait;}));
                $mock->shouldReceive('storeAccount')->once()->andReturn(new \GuzzleHttp\Psr7\Response());
                $mock->shouldReceive('storeContract')->once();
                $mock->shouldReceive('updateProperty')->once();
                $mock->shouldReceive('getAccountByEmail')->once();
                $mock->shouldReceive('getContactByEmail')->once();
                $mock->shouldReceive('getContractsByEmail')->once();
                $mock->shouldReceive('getPropertiesByEmail')->once();
            }
        );
    }
}

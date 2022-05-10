<?php

namespace App\Tests;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
class ApiTest extends HelperApiTestCase
{

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testUsersListing(): void
    {
        $this->client->request('GET', '/api/users');
        $this->assertResponseStatusCodeSame(401);

        $this->login(HelperApiTestCase::$testUserEmail, HelperApiTestCase::$testUserRawPassword);
        $this->assertResponseStatusCodeSame(204);

        $this->client->request('GET', '/api/users');
        $this->assertResponseStatusCodeSame(200);

        $this->client->request('GET', '/logout');
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testHistoriesListing(): void
    {
        $this->client->request('GET', '/api/histories');
        $this->assertResponseStatusCodeSame(401);

        $this->login(HelperApiTestCase::$testUserEmail, HelperApiTestCase::$testUserRawPassword);
        $this->assertResponseStatusCodeSame(204);

        $this->client->request('GET', '/api/histories');
        $this->assertResponseStatusCodeSame(200);

        $this->client->request('GET', '/logout');
    }

    /**
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function testTraderCantReadOtherTrades(): void
    {
        $this->client->request('GET', '/api/histories');
        $this->assertResponseStatusCodeSame(401);

        $this->login(HelperApiTestCase::$testUserEmail, HelperApiTestCase::$testUserRawPassword);
        $this->assertResponseStatusCodeSame(204);

        $this->client->request('GET', '/api/histories');
        $this->assertResponseStatusCodeSame(200);


        $this->assertJsonContains([
            "@type" => "hydra:Collection",
            "hydra:totalItems" => 1,
        ]);
    }
}

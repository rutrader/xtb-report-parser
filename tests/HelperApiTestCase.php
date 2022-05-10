<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;

/**
 * @author Ruslan Ishemgulov <ruslan.ishemgulov@gmail.com>
 */
abstract class HelperApiTestCase extends ApiTestCase
{

//    use ReloadDatabaseTrait;

    public static string $testUserEmail = 'tester@test.loc';

    public static string $testUserEncodedPassword = '$argon2id$v=19$m=65536,t=4,p=1$5L9JHexVRh5jMM99xr1cnQ$cJyI6ndnZZgUbcvqklrd2mUlQz8cIGH3PewU4Ac2dt4';

    public static string $testUserRawPassword = 'foo';

    /** @var object|\Symfony\Component\HttpFoundation\Session\Session|null */
    protected $session;

    /** @var \ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client */
    protected $client;

    /** @var \Doctrine\ORM\EntityManagerInterface|null */
    protected $em;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = static::createClient();

        $this->session = self::getContainer()->get('session');
        $this->em = self::getContainer()->get('doctrine')->getManager();

    }

    protected function tearDown(): void
    {
        parent::tearDown();

    }

    /**
     * @param string $email
     * @param string $password
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    protected function login(string $email, string $password): void
    {
        $this->client->request('POST', '/json/login', [
            'headers' => ['Content-Type' => 'application/json'],
            'json' => [
                'email' => $email,
                'password' => $password
            ],
        ]);
    }

}

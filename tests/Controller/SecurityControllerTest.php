<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, ['username' => 'test', 'password' => 'test']);
        $client->followRedirect();
        $this->assertRouteSame('homepage');
    }

    public static function login(KernelBrowser $client, array $credentials): void
    {
        $client->request('GET', '/login');
        $client->submitForm('Se connecter', [
            '_username' => $credentials['username'],
            '_password' => $credentials['password'],
        ]);
    }
}

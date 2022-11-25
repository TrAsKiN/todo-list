<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHomepageWhenNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testHomepageWhenLoggedIn(): void
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, ['username' => 'test', 'password' => 'test']);
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('homepage');
    }
}

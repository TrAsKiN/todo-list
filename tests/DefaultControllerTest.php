<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testHomepageWhenNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/');

        $this->assertResponseRedirects();
    }

    public function testHomepageWhenLoggedIn(): void
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, ['username' => 'test', 'password' => 'test']);
        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
    }
}

<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $client->submitForm('Se connecter', [
            '_username' => 'test',
            '_password' => 'test',
        ]);
        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertSelectorExists('h1:contains("Bienvenue sur Todo List")');
    }

    public static function login(KernelBrowser $client, array $credentials): void
    {
        $client->request('GET', '/login');
        $client->submitForm('Se connecter', [
            '_username' => $credentials['username'],
            '_password' => $credentials['password'],
        ]);
        $client->followRedirect();
    }
}

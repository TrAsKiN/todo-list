<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TaskControllerTest extends WebTestCase
{
    public function testListWhenNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testListWhenLoggedInAsUser(): void
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, ['username' => 'test', 'password' => 'test']);
        $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_list');
        $this->assertSelectorNotExists('h2:nth-of-type(2)', 'Tâches anonyme');
    }

    public function testListWhenLoggedInAsAdmin(): void
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, ['username' => 'admin', 'password' => 'admin']);
        $client->request('GET', '/tasks');

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('task_list');
        $this->assertSelectorExists('h2:nth-of-type(2)', 'Tâches anonyme');
    }
}

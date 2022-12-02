<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    public function testListWhenNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request(Request::METHOD_GET, '/users');

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testUserListWhenNotAdmin(): void
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, 'user');
        $client->request(Request::METHOD_GET, '/users');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testUserListSuccessfully()
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, 'admin');
        $crawler = $client->request(Request::METHOD_GET, '/users');

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('user_list');
        $this->assertCount(
            count(static::getContainer()->get(UserRepository::class)->findAll()),
            $crawler->filter('table tbody tr'));
    }
}

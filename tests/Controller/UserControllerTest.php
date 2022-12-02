<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\DomCrawler\Form;
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

    public function testUserCreateSuccessfully()
    {
        $client = static::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/users/create');

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('user_create');
        $this->assertInstanceOf(Form::class, $crawler->selectButton('Ajouter')->form());

        $client->submitForm('Ajouter', [
            'user[username]' => 'newuser',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'newuser@user.user',
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame('homepage');
    }

    public function testEditUserWhenNotLoggedIn(): void
    {
        $client = static::createClient();
        $user = static::getContainer()->get(UserRepository::class)->findOneByUsername('newuser');
        $client->request(Request::METHOD_GET, '/users/'. $user->getId() .'/edit');

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertRouteSame('login');
    }

    public function testEditUserWhenNotSelf(): void
    {
        $client = static::createClient();
        SecurityControllerTest::login($client, 'user');
        $user = static::getContainer()->get(UserRepository::class)->findOneByUsername('newuser');
        $client->request(Request::METHOD_GET, '/users/'. $user->getId() .'/edit');

        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testEditUserSuccessfully(): void
    {
        $client = static::createClient();
        $user = SecurityControllerTest::login($client, 'newuser');
        $client->request(Request::METHOD_GET, '/users/'. $user->getId() .'/edit');

        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('user_edit');
        $this->assertRequestAttributeValueSame('id', $user->getId());
        $this->assertInputValueSame('user[username]', $user->getUsername());
        $this->assertInputValueSame('user[email]', $user->getEmail());

        $client->submitForm('Modifier', [
            'user[username]' => 'modifieduser',
            'user[password][first]' => 'password',
            'user[password][second]' => 'password',
            'user[email]' => 'modified@user.user',
        ]);

        $this->assertResponseRedirects();
        $client->followRedirect();
        $this->assertResponseIsSuccessful();
        $this->assertRouteSame('homepage');
        $this->assertSelectorExists('div.alert.alert-success');
    }
}

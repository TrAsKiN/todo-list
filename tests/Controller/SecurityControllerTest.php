<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SecurityControllerTest extends WebTestCase
{
    public function testLogin(): void
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $client->submitForm('Se connecter', [
            '_username' => 'user',
            '_password' => 'user',
        ]);
        $client->followRedirect();
        $this->assertRouteSame('homepage');
    }

    public static function login(KernelBrowser $client, string $as): void
    {
        try {
            $user = static::getContainer()->get(UserRepository::class)->findOneByUsername($as);
            $client->loginUser($user);
        } catch (Exception $e) {}
    }
}

<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserEntity()
    {
        $user = new User;
        $user->setUsername('username')
            ->setEmail('user@email.com')
            ->setPassword('password')
            ->setRoles(['ROLE_USER'])
        ;

        $this->assertEquals('username', $user->getUsername());
        $this->assertEquals('user@email.com', $user->getEmail());
        $this->assertEquals('password', $user->getPassword());
        $this->assertEquals(['ROLE_USER'], $user->getRoles());
    }
}

<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserRepositoryTest extends KernelTestCase
{
    public function testRemove(): void
    {
        self::bootKernel();

        $userRepository = new UserRepository(static::getContainer()->get(ManagerRegistry::class));

        $user = $userRepository->findOneBy(['username' => 'user']);
        $this->assertInstanceOf(User::class, $user);

        $userRepository->remove($user, true);

        $this->assertNull($userRepository->findOneBy(['username' => 'user']));
    }
}

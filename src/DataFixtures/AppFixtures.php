<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private readonly PasswordHasherFactoryInterface $passwordHasher
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $user = (new User())
            ->setUsername('user')
            ->setEmail('user@user.user')
            ->setPassword($this->passwordHasher->getPasswordHasher(User::class)->hash('user'))
        ;
        $manager->persist($user);

        $admin = (new User())
            ->setUsername('admin')
            ->setEmail('admin@admin.admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setPassword($this->passwordHasher->getPasswordHasher(User::class)->hash('admin'))
        ;
        $manager->persist($admin);

        $manager->flush();
    }
}

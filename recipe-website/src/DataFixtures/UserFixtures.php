<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public const ADMIN = 'ADMIN_USER';
    
    public function __construct(
        private readonly UserPasswordHasherInterface $hasher
    ) {}

    public function load(ObjectManager $manager): void
    {
        // Admin user
        $user = new User();
        $user->setRoles(['ROLE_ADMIN'])
            ->setEmail('admin@admin.fr')
            ->setUsername('admin')
            ->setVerified(true)
            ->setPassword($this->hasher->hashPassword($user, 'admin'))
            ->setApiToken('admin_token');
        $this->addReference(self::ADMIN, $user);
        $manager->persist($user);

        // Regular users
        for ($i = 1; $i <= 10; $i++) { 
            $user = new User();
            $user->setRoles(['ROLE_USER'])
                ->setEmail("user{$i}@doe.fr")
                ->setUsername("user{$i}")
                ->setVerified(true)
                ->setPassword($this->hasher->hashPassword($user, '0000'))
                ->setApiToken("user{$i}");
            $this->addReference("USER{$i}", $user);
            $manager->persist($user);
        }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{

    private UserPasswordHasherInterface $hasher;

    public function __construct(UserPasswordHasherInterface $hasher)
    {

        $this->hasher = $hasher;

    }

    public function load(ObjectManager $manager): void
    {
        // $product = new Product();
        // $manager->persist($product);

        $admin = new User();

        $admin
            ->setUserName('admin')
            ->setPassword($this->hasher->hashPassword($admin, 'admin'))
            ->setRoles(['ROLE_ADMIN']);

        $manager->persist($admin);

        $manager->flush();

        # Création d'un deuxième user
        $user = new User();

        $user
            ->setUserName('Poppy')
            ->setPassword($this->hasher->hashPassword($user, 'poppy'))
            ->setRoles(['ROLE_USER']);

        $manager->persist($user);

        $manager->flush();
    }
}

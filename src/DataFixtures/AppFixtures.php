<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    private $passwordHasher;

    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i <= 50; $i++) {
            $user = new User();
            $user->setEmail('user' . $i . '@exemple.com');
            $user->setPassword(
                $this->passwordHasher->hashPassword($user, 'password')
            );

            $manager->persist($user);
        }
        $manager->flush();
    }
}

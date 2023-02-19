<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Produits;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{

    public function __construct(
        private UserPasswordHasherInterface $passwordHasher
    )
    {
    }

    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 10; $i++) {
            $Categorie = new Categorie();
            $Categorie->setName('Categorie' . $i);
            $manager->persist($Categorie);
        }


        for ($i = 1; $i <= 50; $i++) {
            $user = new User();
            $user-> setEmail('user'. $i . '@example.com');
            $user-> setFirstName('Antoine' . $i);
            $user-> setLastName('Bayssac');
            $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
            if ($i === 1) {
                $user->setRoles(['ROLE_ADMIN']);
            }
            $manager->persist($user);

        }

        for ($i = 1; $i <= 200; $i++) {
            $Categorie = new Produits();
            $Categorie->setName('Produits' . $i);
            $manager->persist($Categorie);
        }

        $user->setRoles(['PUBLIC_ACCESS']);
        $manager->flush();
    }
}
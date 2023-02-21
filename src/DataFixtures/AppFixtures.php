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

    public function load(ObjectManager $manager)
    {

        for ($i = 1; $i <= 10; $i++) {
            $categorie = new Categorie();
            $categorie->setName('Catégorie ' . $i);

            $image = sprintf('https://picsum.photos/400/300?random=%d', $i);
            $categorie->setImage($image);

            $manager->persist($categorie);
        }

        $manager->flush();


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

        $categories = $manager->getRepository(Categorie::class)->findAll();

        $produit_groups = array_chunk(range(1, 200), 20);

        foreach ($produit_groups as $index => $produit_group) {
            foreach ($produit_group as $produit_id) {
                $produit = new Produits();
                $produit->setName('Produit ' . $produit_id);
                $produit->setPrix(mt_rand(100, 1000));
                $produit->setStock(mt_rand(10, 100));
                $produit->setDescription('Description du produit ' . $produit_id);
                $image = sprintf('https://picsum.photos/400/300?random=%d', $produit_id);
                $produit->setImage($image);

                // attribution de la catégorie correspondante
                $categorie = $categories[($index + $produit_id) % 10];
                $produit->setCategorie($categorie);

                $manager->persist($produit);
            }
        }

        $manager->flush();

        $user->setRoles(['PUBLIC_ACCESS']);
        $manager->flush();
    }
}
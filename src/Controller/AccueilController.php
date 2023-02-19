<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'accueil_app')]
    public function index(CategorieRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categorie/{id}', name: 'categorie_app')]
    public function categorie($id, CategorieRepository $categoryRepository): Response
    {
        $categorie = $categoryRepository->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException('Categorie not found');
        }

        return $this->render('categorie/index.html.twig', [
            'categorie' => $categorie,
            'produits' => $categorie->getProduits()
        ]);
    }

    #[Route('/produit/{id}', name: 'app_produit')]
    public function produits($id, ProduitsRepository $produitsRepository): Response
    {
        $produits = $produitsRepository->find($id);

        if (!$produits) {
            throw $this->createNotFoundException('Produits not found');
        }

        return $this->render('categorie/index.html.twig', [
            'produits' => $produits,
        ]);
    }
}

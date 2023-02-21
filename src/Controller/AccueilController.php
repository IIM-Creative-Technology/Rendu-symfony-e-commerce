<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\CategorieRepository;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccueilController extends AbstractController
{
    #[Route('/accueil', name: 'app_accueil')]
    public function index(CategorieRepository $categoryRepository): Response
    {
        $categories = $categoryRepository->findAll();

        return $this->render('accueil/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    #[Route('/categorie/{id}', name: 'categorie_app')]
    public function categorie($id, Request $request, CategorieRepository $categoryRepository, ProduitsRepository $produitsRepository): Response
    {
        $categorie = $categoryRepository->find($id);

        if (!$categorie) {
            throw $this->createNotFoundException('Categorie not found');
        }

        $sort = $request->request->get('sort');

        if ($sort === 'desc') {
            $produits = $produitsRepository->findBy(
                ['categorie' => $categorie],
                ['prix' => 'DESC']
            );
        } elseif ($sort === 'asc') {
            $produits = $produitsRepository->findBy(
                ['categorie' => $categorie],
                ['prix' => 'ASC']
            );
        } else {
            $produits = $categorie->getProduits();
        }

        return $this->render('categorie/index.html.twig', [
            'categorie' => $categorie,
            'produits' => $produits
        ]);
    }

    #[Route('/produit/{id}', name: 'produit_app')]
    public function produits($id, ProduitsRepository $produitsRepository): Response
    {
        $produits = $produitsRepository->find($id);

        if (!$produits) {
            throw $this->createNotFoundException('Produits not found');
        }

        return $this->render('produit/index.html.twig', [
            'produits' => $produits,
        ]);
    }
}

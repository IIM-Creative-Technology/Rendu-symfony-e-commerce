<?php

namespace App\Controller;

use App\Entity\Produits;
use App\Repository\ProduitsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class PanierController extends AbstractController
{
    #[Route('/panier', name: 'app_panier')]
    public function panier(SessionInterface $session, ProduitsRepository $produitsRepository)
    {
        $cart = $session->get('cart', []);
        $produits = [];

        foreach ($cart as $id => $item) {
            $produit = $produitsRepository->find($id);
            if (!$produit) {
                continue;
            }
            $produits[] = [
                'produit' => $produit,
                'quantite' => $item['quantite'],
            ];
        }

        return $this->render('panier/index.html.twig', [
            'produits' => $produits,
        ]);
    }

    #[Route('/ajouter-au-panier/{id}', name: 'ajouter_au_panier')]
    public function ajouterAuPanier($id, SessionInterface $session, ProduitsRepository $produitsRepository)
    {
        $cart = $session->get('cart', []);

        $produit = $produitsRepository->find($id);
        if (!$produit) {
            return $this->redirectToRoute('app_homepage');
        }

        if (array_key_exists($id, $cart)) {
            $cart[$id]['quantite']++;
        } else {
            $cart[$id] = [
                'id' => $id,
                'quantite' => 1,
            ];
        }

        $session->set('cart', $cart);

        return $this->redirectToRoute('app_panier');
    }

    #[Route('/supprimer-du-panier/{id}', name: 'supprimer_du_panier')]
    public function supprimerDuPanier($id, SessionInterface $session)
    {
        // Get the current cart from the session, or create a new one if it doesn't exist
        $cart = $session->get('cart', []);

        // Remove the product from the cart
        unset($cart[$id]);

        // Save the updated cart back to the session
        $session->set('cart', $cart);

        // Redirect to the cart page to show the updated cart
        return $this->redirectToRoute('app_panier');
    }
}
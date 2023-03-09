<?php

// src/Service/PanierService.php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class PanierService
{
    private $session;
    private $produitRepository;

    public function __construct(SessionInterface $session, EntityManagerInterface $entityManager)
    {
        $this->session = $session;
        $this->produitRepository = $entityManager->getRepository('App:Produit');
    }

    // ...
    public function getPanier()
    {
        $panier = $this->session->get('panier', []);
        $panierWithData = [];
        foreach ($panier as $id => $quantity) {
            $panierWithData[] = [
                'produit' => $this->produitRepository->find($id),
                'quantity' => $quantity
            ];
        }
        return $panierWithData;
    }

    public function addProduit($id)
    {
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id])) {
            $panier[$id]++;
        } else {
            $panier[$id] = 1;
        }
        $this->session->set('panier', $panier);
    }

    public function removeProduit($id)
    {
        $panier = $this->session->get('panier', []);
        if (!empty($panier[$id])) {
            unset($panier[$id]);
        }
        $this->session->set('panier', $panier);
    }

    public function getProduitCount()
    {
        $panier = $this->session->get('panier', []);
        $count = 0;
        foreach ($panier as $id => $quantity) {
            $count += $quantity;
        }
        return $count;
    }

    public function getTotal()
    {
        $panier = $this->session->get('panier', []);
        $total = 0;
        foreach ($panier as $id => $quantity) {
            $produit = $this->produitRepository->find($id);
            $total += $produit->getPrix() * $quantity;
        }
        return $total;
    }
}

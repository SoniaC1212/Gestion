<?php

namespace App\Controller;

use App\Repository\ProduitRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    #[Route('/panier', name: 'app_cart')]
    public function index(SessionInterface $session, ProduitRepository $produitRepository)
    {
        $panier = $session->get('panier',[]);
        $panierWithData = [];

        foreach ($panier as $id => $quantity){
          $panierWithData[] = [
              'produit' => $produitRepository->find($id),
              'quantity' => $quantity
          ];
        }
        $total = 0;
        foreach ($panierWithData as $item){
            $totalItem = $item['produit']->getPrixProduit() * $item['quantity'];
            $total += $totalItem;
        }


        return $this->render('cart/index.html.twig', [
            'items' => $panierWithData,
            'total' => $total

        ]);
    }


    #[Route('/panier/add/{id}', name: 'add_cart')]
    public function add($id, SessionInterface $session)
    {
        $panier = $session->get('panier',[]);
        if(!empty($panier[$id])){
            $panier[$id]++;
        }else {
            $panier[$id] = 1;
        }

        $session->set('panier', $panier);
        return $this->redirectToRoute("app_cart");
    }

    #[Route('/panier/remove/{id}', name: 'remove_cart')]
    public function remove($id, SessionInterface $session)
    {
        $panier = $session->get('panier',[]);

        if(!empty($panier[$id])){
            unset($panier[$id]);

        }

        $session->set('panier', $panier);
        return $this->redirectToRoute("app_cart");
    }

}

<?php

namespace App\Controller;

use App\Entity\Produit;
use App\Repository\ProduitRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

class ProduitApiController extends AbstractController
{
    #[Route('/produitapi', name: 'app_produit_api')]
    public function index(): Response
    {
        return $this->render('produit_api/index.html.twig', [
            'controller_name' => 'ProduitApiController',
        ]);
    }


    #[Route('/api/listproduit', name: 'app_listproduit_api')]
    public function getProduits(ProduitRepository $repo,SerializerInterface $serializerInterface)
    {
        $produits=$repo->findAll();
        $json=$serializerInterface->serialize($produits,'json',['groups'=>'produits']);
        dump($produits);
        die;
    }

    #[Route('/api/addproduit', name: 'app_addproduit_api')]
    public function addproduit(Request $request,SerializerInterface $serializer,EntityManagerInterface $em){
        $content=$request->getContent();
        $data=$serializer->deserialize($content,Produit::class,'json');
        $em->persist($data);
        $em->flush();
        return new JsonResponse('produit ajouter avec succes!');
    }

    #[Route('/api/updateproduit/{id}', name: 'app_updateproduit_api', methods: ['PUT'])]
    public function updateproduit(Request $request, Produit $produit, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $content = $request->getContent();
        $data = $serializer->deserialize($content, Produit::class, 'json');

        $produit->setNom($data->getNom());
        $produit->setDescription($data->getDescription());
        $produit->setPrix($data->getPrix());
        $produit->setCategorie($data->getCategorie());

        $em->flush();

        return new Response('produit modifié avec succès!');
    }

    #[Route('/api/deleteproduit/{id}', name: 'app_deleteproduit_api', methods: ['DELETE'])]
    public function deleteproduit(Produit $produit, EntityManagerInterface $em)
    {
        $em->remove($produit);
        $em->flush();

        return new Response('produit supprimé avec succès!');
    }

}

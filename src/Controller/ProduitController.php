<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\ProduitRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Produit;
use App\Form\ProduitType;
use App\Service\PanierService;



class ProduitController extends AbstractController
{
    #[Route('/produit', name: 'app_produit')]
    public function index(ProduitRepository $produitRepository): Response
    {
        return $this->render('produit/index.html.twig', [
            'produit' => $produitRepository->findAll(),
        ]);
    }

    #[Route('/admin', name: 'display_admin')]
    public function indexAdmin(): Response
    {
        return $this->render('admin/base-back.html.twig',

        );
    }



    #[Route('/list', name: 'list_produit')]
    public function afficher(ManagerRegistry $doctrine): Response
    {
        $repository= $doctrine->getRepository(Produit::class);

        $Produit=$repository->findall();
        return $this->render('Produit/list.html.twig', [
            'produit' => $Produit,
        ]);


    }

    #[Route('/supp/{id}', name: 's')]
   
    public function supprimer($id,request $request): Response
    {
        
        $Produit=$this->getDoctrine()->getRepository(Produit::class)->find($id);
        $em= $this->getDoctrine()->getManager(); 
       $em->remove($Produit);
       $em->flush();
        return $this->redirectToRoute('list_produit');


    }


    #[Route('/add', name: 'a')]

    public function ajouter(Request $request)
    {
        $Produit= new Produit();
        $form=$this->createForm(ProduitType::class,$Produit);
        $form->add('Ajouter', SubmitType::class);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid())
        {
         $em=$this->getdoctrine()->getManager();
         $em->persist($Produit);
         $em->flush();

            return $this->redirectToRoute('app_produit');
        }
        return $this->render('produit/Add.html.twig',[

            'form'=>$form->createView()
        ]);


    }

    #[Route('/up/{id}', name: 'u')]

    public function update(ProduitRepository $repository,Request $request ,$id)
    {
        $Produit=$repository->find($id);
        $form=$this->createForm(ProduitType::class,$Produit);
        $form->add('modifier', SubmitType::class);
        $form->handleRequest($request);


        if( $form->isSubmitted() && $form->isValid())
        {
         $em=$this->getdoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('list_produit');

        }
        return $this->render('produit/update.html.twig',
        [
            'f'=>$form->createView()
        ]);
    }


   
}

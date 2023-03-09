<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\CategorieRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Categorie;
use App\Form\CategorieType;

class CategorieController extends AbstractController
{
    #[Route('/categorie', name: 'app_categorie')]
    public function index(): Response
    {
        return $this->render('categorie/index.html.twig', [
            'controller_name' => 'CategorieController',
        ]);
    }

    #[Route('/listS', name: 'list_categorie')]
    public function afficher(ManagerRegistry $doctrine): Response
    {
        $repository= $doctrine->getRepository(Categorie::class); 

        $Categorie=$repository->findall();
        return $this->render('categorie/listS.html.twig', [
            'categorie' => $Categorie,
        ]);


    }


    #[Route('/suppS/{id}', name: 'ss')]
   
    public function supprimer($id,request $request ): Response
    {
        
        $Categorie=$this->getDoctrine()->getRepository(Categorie::class)->find($id);
        $em= $this->getDoctrine()->getManager(); 
       $em->remove($Categorie);
       $em->flush();
        return $this->redirectToRoute('list_categorie');


    }
    
    #[Route('/addS', name: 'as')]

    public function ajouter(Request $request)
    {
        $Categorie= new Categorie();
        $form=$this->createForm(CategorieType::class,$Categorie);
        $form->add('Ajouter', SubmitType::class);

        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid())
        {
         $em=$this->getdoctrine()->getManager();
         $em->persist($Categorie);
         $em->flush();

         return $this->redirectToRoute('list_categorie');

        }
        return $this->render('categorie/AddS.html.twig',[

            'form2'=>$form->createView()
        ]);


    }


    #[Route('/upp/{id}', name: 'up')]

    public function update(CategorieRepository $repository,Request $request ,$id)
    {
        $Categorie=$repository->find($id);
        $form=$this->createForm(CategorieType::class,$Categorie);
        $form->add('modifier', SubmitType::class);
        $form->handleRequest($request);


        if( $form->isSubmitted() && $form->isValid())
        {
         $em=$this->getdoctrine()->getManager();
        $em->flush();
        return $this->redirectToRoute('list_categorie');

        }
        return $this->render('categorie/updates.html.twig',
        [
            'fs'=>$form->createView()
        ]);
    }

}

<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Cours;
use App\Form\CoursType;
use App\Repository\CoursRepository;
use Doctrine\Persistence\ManagerRegistry;

/*orm->provide by doctrine-> technique de mapping car daabase de donne relationnel donc elle ne peut pas comprender les objets et notre projets oriente
** objet 
**creation du controlleur :php bin/console make :controller
**render:take me to this replacement 
**every time i create controller ->index.html.twig will be created
**--------------create :copy paste from public fct index 
**1)creation du form("matieretype"ajout de attribut )
**handlerequest->httpfoundation donnnes de form send request toserver
**3_test if form is submitted (click on bottom to add ater completing formfrom should be valid
**em->has fct predifini de l ajout 
**persist ->lajout de nouv objet 
**flush->commit modification after persist 
**Un Repository Doctrine est un objet dont la responsabilité est de récupérer une collection d'objets.
** Les repositories ont accès à deux objets principalement : EntityManager : Permet de manipuler nos entités 
**; QueryBuilder : Un constructeur de requêtes qui permet de créer des requêtes personnalisé
*/

class CoursController extends AbstractController
{
    #[Route('/cours', name: 'app_cours')]
    public function index( CoursRepository $repository): Response
    {
        return $this->render('cours/affiche_cours.html.twig', [

                'candidats' => $repository->findAll(),
        ]);
    }

    #[Route('/suppCours/{id}', name: 'supprimerC')]

    public function suppC(ManagerRegistry $doctrine,$id,CoursRepository $repository)
      {
      //récupérer le Cours à supprimer
          $Cours= $repository->find($id);
      //récupérer l'entity manager
          $em= $doctrine->getManager();
          $em->remove($Cours);
          $em->flush();
          return $this->redirectToRoute("app_addCours");///////////////////////
      } 

      #[Route('/updatCours/{id}', name: 'updatC')]

    public function updatC(ManagerRegistry $doctrine,$id,CoursRepository $repository,Request $request)
      {
      //récupérer le Cours à supprimer
      
          $c= $repository->findAll();
          $Cours= $repository->find($id);
          $newCours= new Cours();
          $form=$this->createForm(CoursType::class,$newCours);
          $form->get('libelle')->setData($Cours->getLibelle());
          $form->get('niveau')->setData($Cours->getNiveau());
          $form->get('note')->setData($Cours->getNote());
          
         
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $file = $form['image']->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );
            $newCours->setImage($fileName);
            $em =$doctrine->getManager() ;
            $Cours->setLibelle($newCours->getLibelle());
            $Cours->setNiveau($newCours->getNiveau());
            $Cours->setNote($newCours->getNote());
            $Cours->setImage($newCours->getImage());
            $em->flush();
            return $this->redirectToRoute("app_addCours");
        }
        else {
            // Form is not valid, retrieve the errors
            $errors = $form->getErrors(true, false);

            // Display the errors

        }
        return $this->render("cours/addCours.html.twig", [
            'formClass' => $form->createView(),
            "Cours"=>$c,
        ]);
      } 

    

      #[Route('/afficheCours/{id}', name: 'afficheCours')]

      public function showC(CoursRepository $repository)
      {
          $c= $repository->findAll();
          return $this->render("Cours/showCours.html.twig",
          ["Cours"=>$c]);
        }
 
             

 #[Route('/addCours', name: 'app_addCours')]
    public function addCours(ManagerRegistry $doctrine,Request $request,CoursRepository $repository)
    {
        $c= $repository->findAll();
        $Cours= new Cours();
        $form=$this->createForm(CoursType::class,$Cours);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $file = $form['image']->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );
            $Cours->setImage($fileName);
            $em =$doctrine->getManager() ;
            $em->persist($Cours);
            $em->flush();
            return $this->redirectToRoute("app_addCours");
        }
        else {
            // Form is not valid, retrieve the errors
            $errors = $form->getErrors();
        }
       
        return $this->render("cours/addCours.html.twig", [
        'formClass' => $form->createView(),
        "Cours"=>$c,
        
    ]);
     }


    #[Route('/afficheAllCour', name: 'afficheallcour')]
    public function  affichier(CoursRepository $repository):Response
    {
        # affichier tous les cours
        $users = $repository->findAll();
        return $this->render('cours/affiche_cours.html.twig', ['users' => $users]);
    }




}

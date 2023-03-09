<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Matiere;
use App\Form\MatiereType;
use App\Repository\MatiereRepository;
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
*/

class MatiereController extends AbstractController
{
    #[Route('/matiere', name: 'app_matiere')]
    public function index(MatiereRepository $repository): Response
    {
        return $this->render('matiere/affiche_matiers.html.twig', [
            'candidats' => $repository->findAll(),
        ]);
    }

    #[Route('/suppMatiere/{id}', name: 'supprimerM')]

    public function suppC(ManagerRegistry $doctrine,$id,MatiereRepository $repository)
      {
      //récupérer le Matiere à supprimer
          $Matiere= $repository->find($id);
      //récupérer l'entity manager
          $em= $doctrine->getManager();
          $em->remove($Matiere);
          $em->flush();
          return $this->redirectToRoute("app_addMatiere");///////////////////////
      } 
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
**class in the Doctrine ORM library for PHP that provides a way to manage multiple instances of EntityManager. 
An EntityManager is responsible for managing the persistence of objects to a database. The ManagerRegistry class 
provides a central location to 
retrieve instances of EntityManager and to access metadata about entities mapped in the application. 
*/

      #[Route('/updatMatiere/{id}', name: 'updatM')]

    public function updatC(ManagerRegistry $doctrine,$id,MatiereRepository $repository,Request $request)
      {
      //récupérer le Matiere à supprimer
      
          $c= $repository->findAll();
          $Matiere= $repository->find($id);
          $newMatiere= new Matiere();
          $form=$this->createForm(MatiereType::class,$newMatiere);
          $form->get('libelle')->setData($Matiere->getLibelle());
          /*
          **and put it in images directry (defini roots.yaml yhiz lil public /images*/
          
         
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $file = $form['image']->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );
            $newMatiere->setImage($fileName);
            $em =$doctrine->getManager() ;
            $Matiere->setLibelle($newMatiere->getLibelle());
            $Matiere->setImage($newMatiere->getImage());
            $em->flush();
            return $this->redirectToRoute("app_addMatiere");
        }
        else {
            // Form is not valid, retrieve the errors
            $errors = $form->getErrors(true, false);

            // Display the errors
            foreach ($errors as $error) {
                $message = $error->getMessage();
            }
        }
        return $this->render("matiere/addMatiere.html.twig", [
            'formClass' => $form->createView(),
            "Matiere"=>$c,
        ]);
      } 

    

      #[Route('/afficheMatiere', name: 'afficheMatiere')]

      public function showC(MatiereRepository $repository)
      {
          $c= $repository->findAll();
          return $this->render("Matiere/showMatiere.html.twig",
          ["Matiere"=>$c]);
        }


        

         
             

 #[Route('/addMatiere', name: 'app_addMatiere')]
    public function addMatiere(ManagerRegistry $doctrine,Request $request,MatiereRepository $repository)
    {
        $c= $repository->findAll();
        $Matiere= new Matiere();
        $form=$this->createForm(MatiereType::class,$Matiere);
        $form->handleRequest($request);
        if($form->isSubmitted()&& $form->isValid()){
            $file = $form['image']->getData();
            $fileName = md5(uniqid()).'.'.$file->guessExtension();
            $file->move(
                $this->getParameter('images_directory'),
                $fileName
            );
            $Matiere->setImage($fileName);
            $em =$doctrine->getManager() ;
            $em->persist($Matiere);
            $em->flush();
            return $this->redirectToRoute("app_addMatiere");
        }
        else {
            // Form is not valid, retrieve the errors
            $errors = $form->getErrors();
        }
       
        return $this->render("matiere/addMatiere.html.twig", [
        'formClass' => $form->createView(),
        "Matiere"=>$c,
        
    ]);
     }
}

<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Document;
use App\Form\CommandeType;
use App\Repository\CommandeRepository;
use App\Repository\DocumentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/commande')]
class CommandeController extends AbstractController
{
    #[Route('/', name: 'app_commande_index', methods: ['GET'])]
    public function index(CommandeRepository $commandeRepository, Request $request, PaginatorInterface $paginator): Response
    {  $query = $commandeRepository->createQueryBuilder('d')
        ->orderBy('d.id', 'DESC')
        ->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query to paginate */
            $request->query->getInt('page', 1), /* current page number */
            10);/* number of items per page */
        return $this->render('commande/index.html.twig', [
            'commandes' => $commandeRepository->findAll(),
            'commandes' => $pagination,

        ]);
    }

    #[Route('/new', name: 'app_commande_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CommandeRepository $commandeRepository): Response
    {
        $commande = new Commande();
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->save($commande, true);

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/new.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_show', methods: ['GET'])]
    public function show(Commande $commande): Response
    {
        return $this->render('commande/show.html.twig', [
            'commande' => $commande,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_commande_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        $form = $this->createForm(CommandeType::class, $commande);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $commandeRepository->save($commande, true);

            return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('commande/edit.html.twig', [
            'commande' => $commande,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_commande_delete', methods: ['POST'])]
    public function delete(Request $request, Commande $commande, CommandeRepository $commandeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$commande->getId(), $request->request->get('_token'))) {
            $commandeRepository->remove($commande, true);
        }

        return $this->redirectToRoute('app_commande_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/commande/search', name: 'commande_search', methods: ['POST'])]

    public function search (Request $request , CommandeRepository $commandeRepository ): Response
    {

        $searchQuery = $request->request->get('search');



        // Cherche le document par nom
        $results = $this->getDoctrine()->getRepository(Commande::class)->findOneBy(['nomdocument' => $searchQuery]);




        $data = array(
            "id" => $results->getId(),
            "nom" => $results->getnomdocument(),
            "editeur" =>$results->getediteur(),
            "type" => $results->gettype(),
        );


        return new JsonResponse($data);







    }






























}

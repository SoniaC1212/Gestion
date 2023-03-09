<?php

namespace App\Controller;

use App\Entity\Commande;
use App\Entity\Document;
use App\Form\DocumentSearchType;
use App\Form\DocumentType;
use App\Repository\CommandeRepository;
use App\Repository\DocumentRepository;
use Doctrine\ORM\EntityManagerInterface;
use JetBrains\PhpStorm\NoReturn;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

#[Route('/document')]
class DocumentController extends AbstractController
{
    #[Route('/', name: 'app_document_index', methods: ['GET'])]
    public function index(DocumentRepository $documentRepository, Request $request, PaginatorInterface $paginator): Response
    {        $query = $documentRepository->createQueryBuilder('d')
        ->orderBy('d.id', 'DESC')
        ->getQuery();

        $pagination = $paginator->paginate(
            $query, /* query to paginate */
            $request->query->getInt('page', 1), /* current page number */
            10);/* number of items per page */
        return $this->render('document/index.html.twig', [
            'documents' => $documentRepository->findAll(),
            'documents' => $pagination,

        ]);
    }

    #[Route('/new', name: 'app_document_new', methods: ['GET', 'POST'])]
    public function new(Request $request, DocumentRepository $documentRepository , SluggerInterface $slugger ): Response
    {
        $document = new Document();

        $form = $this->createForm(DocumentType::class, $document);
        //dd("a");
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            ($request);
            $entityManager = $this->getDoctrine()->getManager();

            // Récupère la nouvelle URL entrée dans le formulaire
            $url = $form->get('url')->getData();

            // Modifie l'URL du produit avec la nouvelle URL
            $document->setUrl($url);

            $entityManager->persist($document);
            $entityManager->flush();

            $imageFile = $form->get('image')->getData();
            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('documents_images'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }
                $document->setImage($newFilename);
            }


            // Move the file to the directory where images are stored

            $documentRepository->save($document, true);

            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('document/new.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/show', name: 'app_document_show', methods: ['GET'] )]
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_document_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Document $document, DocumentRepository $documentRepository): Response
    { 
        $document->setImage(null);
        $form = $this->createForm(DocumentType::class, $document);

        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
            $documentRepository->save($document, true);
            return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('document/edit.html.twig', [
            'document' => $document,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_document_delete', methods: ['POST'])]
    public function delete(Request $request, Document $document, DocumentRepository $documentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $documentRepository->remove($document, true);
        }

        return $this->redirectToRoute('app_document_index', [], Response::HTTP_SEE_OTHER);
    }


    #[Route('/document/search', name: 'document_search', methods: ['POST'])]


    public function search (Request $request , DocumentRepository $documentRepository ): Response
    {

        $searchQuery = $request->request->get('search');



        // Cherche le document par nom
        $results = $this->getDoctrine()->getRepository(Document::class)->findOneBy(['nomdocument' => $searchQuery]);




        $data = array(
            "id" => $results->getId(),
            "nom" => $results->getnomdocument(),
            "editeur" =>$results->getediteur(),
            "type" => $results->gettype(),
            "image" => $results->getimage(),

        );


        return new JsonResponse($data);






    }








































}

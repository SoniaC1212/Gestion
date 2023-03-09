<?php

namespace App\Controller;


use Dompdf\Dompdf;
use Dompdf\Options;
use App\Entity\Offre;
use App\Form\OffreType;
use App\Repository\OffreRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\mailler;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;



#[Route('/offre')]
class OffreController extends AbstractController
{
    #[Route('/', name: 'app_offre_index', methods: ['GET'])]
    public function index(OffreRepository $offreRepository): Response
    {
        return $this->render('offre/index.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }

    #[Route('/exportPDF', name: 'exportPDF')]
    public function Print(EntityManagerInterface $entityManager): Response
    {
        $pdfoptions = new Options();

        $pdfoptions->set('defaultFont', 'Arial');
        $pdfoptions->setIsRemoteEnabled(true);

        $dompdf = new Dompdf($pdfoptions);

        $offre = $entityManager
            ->getRepository(offre::class)
            ->findAll();

        $html = $this->renderView('offre/pdfExport.html.twig', [
            'offres' => $offre
        ]);

        $html = '<div>' . $html . '</div>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $pdfOutput = $dompdf->output();

        return new Response($pdfOutput, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="offrePDF.pdf"'
        ]);
    }

    #[Route('/lista', name: 'app_offre_lista')]
    public function getOffres(OffreRepository $offreRepository, SerializerInterface $serializerInterface)
    {
        $offres = $offreRepository->findAll();
        $json = $serializerInterface->serialize($offres, 'json', ['groups' => 'offres']);
        dump($offres);
        die;

        $response = new Response(
            $json,
            200,
            ["Content-Type" => "application/json"]
        );
        return $response;
    }
    #[Route('/addd', name: 'app_offre_addd_offre')]
    public function adddoffre(Request $request, SerializerInterface $serializer, EntityManagerInterface $em)
    {
        $content = $request->getContent();
        $data = $serializer->deserialize($content, Offre::class, "json");
        $em->persist($data);
        $em->flush();
        return new Response('student added successfully');
    }

    #[Route('/viewOffres', name: 'app_offre_index_front', methods: ['GET'])]
    public function indexFront(OffreRepository $offreRepository): Response
    {
        return $this->render('offre/indexFront.html.twig', [
            'offres' => $offreRepository->findAll(),
        ]);
    }

    #[Route('/newOffre', name: 'app_offre_new_front', methods: ['GET', 'POST'])]
    public function newFront(Request $request, OffreRepository $offreRepository, MailerInterface $mailer): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) { {
                $offreRepository->save($offre, true);
                $email = (new Email())
                    ->from('Edu4u@gmail.com')
                    ->to('exemple@gmail.com')
                    ->subject('Ajout est valide')
                    ->text('offre est ajoutée avec succées');

                $mailer->send($email);
            }
            return $this->redirectToRoute('app_offre_index_front', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/newFront.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/new', name: 'app_offre_new', methods: ['GET', 'POST'])]
    public function new(Request $request, OffreRepository $offreRepository): Response
    {
        $offre = new Offre();
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offreRepository->save($offre, true);

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/new.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offre_show', methods: ['GET'])]
    public function show(Offre $offre): Response
    {
        return $this->render('offre/show.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/viewOffre/{id}', name: 'app_offre_show_front', methods: ['GET'])]
    public function showFront(Offre $offre): Response
    {
        return $this->render('offre/showFront.html.twig', [
            'offre' => $offre,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_offre_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Offre $offre, OffreRepository $offreRepository): Response
    {
        $form = $this->createForm(OffreType::class, $offre);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $offreRepository->save($offre, true);

            return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('offre/edit.html.twig', [
            'offre' => $offre,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_offre_delete', methods: ['POST'])]
    public function delete(Request $request, Offre $offre, OffreRepository $offreRepository): Response
    {
        if ($this->isCsrfTokenValid('delete' . $offre->getId(), $request->request->get('_token'))) {
            $offreRepository->remove($offre, true);
        }

        return $this->redirectToRoute('app_offre_index', [], Response::HTTP_SEE_OTHER);
    }
}

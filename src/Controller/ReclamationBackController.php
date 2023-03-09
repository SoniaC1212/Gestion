<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Repository\ReclamationRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use CMEN\GoogleChartsBundle\GoogleCharts\Charts\PieChart;

use Dompdf\Dompdf;
use Dompdf\Options;

#[Route('/dashboard/reclamation')]
class ReclamationBackController extends AbstractController
{
    #[Route('/', name: 'admin_reclamation_index', methods: ['GET'])]
    public function index(ReclamationRepository $reclamationRepository, Request $request, PaginatorInterface $paginator): Response
    {
            
        // Méthode findBy qui permet de récupérer les données avec des critères de filtre et de tri
        $data = $this->getDoctrine()->getRepository(Reclamation::class)->findBy([], ['id' => 'desc']);

        $pagination = $paginator->paginate(
            $data, // Requête contenant les données à paginer (ici nos reclamations enregistrés)
            $request->query->getInt('page', 1), // Numéro de la page en reclamation, passé dans l'URL, 1 si aucune page
            4 // Nombre de résultats par page
        );

        $pieChart = new PieChart();

        $reclamationsData = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
        $typesData = array('Connexion', 'Acces',
                'Qualité du contenu',
                'Don',
                'Feedback'
        );

        // dd($typesData);

        $charts = array(['Reclamations', 'Number per Type']);
        // dd($charts);
        foreach ($typesData as $t) {
            $typeN = 0;
            foreach ($reclamationsData as $r) {
                if ($t == $r->getType()) {
                    $typeN++;
                    // dd($t);
                }
            }

            array_push($charts, [$t, $typeN]);
            // dd($charts);
        }


        // dd($charts);

        $pieChart->getData()->setArrayToDataTable(
            $charts
        );

        // dd($pieChart);

        $pieChart->getOptions()->setTitle('Reclamations Number per Type');
        $pieChart->getOptions()->setHeight(400);
        $pieChart->getOptions()->setWidth(400);
        $pieChart->getOptions()->getTitleTextStyle()->setColor('#07600');
        $pieChart->getOptions()->getTitleTextStyle()->setFontSize(25);

        return $this->render('reclamationBack/index.html.twig', [
            'reclamations' => $pagination,
            // 'reclamations' => $reclamationRepository->findAll(),
            'piechart' => $pieChart
        ]);
    }
    
    #[Route('/sortByAscDate', name: 'sort_by_asc_date')]
    public function sortAscDate(ReclamationRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        $reclamations = $repository->findAll();
        
        $query = $request->query->get('q');
        $reclamations = $this->getDoctrine()
            ->getRepository(Reclamation::class)
            ->searchReclamation($query);
        
        $data = $this->getDoctrine()
            ->getRepository(Reclamation::class)
            ->findBy([], ['id' => 'desc']);
    
            $pagination = $paginator->paginate(
                $data, // Requête contenant les données à paginer (ici nos reclamations enregistrés)
                $request->query->getInt('page', 1), // Numéro de la page en reclamation, passé dans l'URL, 1 si aucune page
                4 // Nombre de résultats par page
            );
    
            $pieChart = new PieChart();
    
            $reclamationsData = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
            $typesData = array('Connexion', 'Acces',
                    'Qualité du contenu',
                    'Don',
                    'Feedback'
            );
    
            // dd($typesData);
    
            $charts = array(['Reclamations', 'Number per Type']);
            // dd($charts);
            foreach ($typesData as $t) {
                $typeN = 0;
                foreach ($reclamationsData as $r) {
                    if ($t == $r->getType()) {
                        $typeN++;
                        // dd($t);
                    }
                }
    
                array_push($charts, [$t, $typeN]);
                // dd($charts);
            }
    
    
            // dd($charts);
    
            $pieChart->getData()->setArrayToDataTable(
                $charts
            );
    
            // dd($pieChart);
    
            $pieChart->getOptions()->setTitle('Reclamations Number per Type');
            $pieChart->getOptions()->setHeight(400);
            $pieChart->getOptions()->setWidth(400);
            $pieChart->getOptions()->getTitleTextStyle()->setColor('#07600');
            $pieChart->getOptions()->getTitleTextStyle()->setFontSize(25);
            
            $pagination = $repository->sortByAscDate();   
    
        return $this->render("reclamationBack/index.html.twig",[
            'piechart' => $pieChart,
            'reclamations' => $pagination,
            'query' => $query
        ]);
    }
    
    #[Route('/sortByDescDate', name: 'sort_by_desc_date')]
    public function sortDescDate(ReclamationRepository $repository, Request $request, PaginatorInterface $paginator)
    {
        $reclamations = $repository->findAll();

        $query = $request->query->get('q');
        $reclamations = $this->getDoctrine()
            ->getRepository(Reclamation::class)
            ->searchReclamation($query);
                
        
        $data = $this->getDoctrine()
            ->getRepository(Reclamation::class)
            ->findBy([], ['id' => 'desc']);
    
            $pagination = $paginator->paginate(
                $data, // Requête contenant les données à paginer (ici nos reclamations enregistrés)
                $request->query->getInt('page', 1), // Numéro de la page en reclamation, passé dans l'URL, 1 si aucune page
                4 // Nombre de résultats par page
            );
    
            $pieChart = new PieChart();
    
            $reclamationsData = $this->getDoctrine()->getRepository(Reclamation::class)->findAll();
            $typesData = array('Connexion', 'Acces',
                    'Qualité du contenu',
                    'Don',
                    'Feedback'
            );
    
            // dd($typesData);
    
            $charts = array(['Reclamations', 'Number per Type']);
            // dd($charts);
            foreach ($typesData as $t) {
                $typeN = 0;
                foreach ($reclamationsData as $r) {
                    if ($t == $r->getType()) {
                        $typeN++;
                        // dd($t);
                    }
                }
    
                array_push($charts, [$t, $typeN]);
                // dd($charts);
            }
    
    
            // dd($charts);
    
            $pieChart->getData()->setArrayToDataTable(
                $charts
            );
    
            // dd($pieChart);
    
            $pieChart->getOptions()->setTitle('Reclamations Number per Type');
            $pieChart->getOptions()->setHeight(400);
            $pieChart->getOptions()->setWidth(400);
            $pieChart->getOptions()->getTitleTextStyle()->setColor('#07600');
            $pieChart->getOptions()->getTitleTextStyle()->setFontSize(25);
            
        $pagination = $repository->sortByDescDate();   
    
        return $this->render("reclamationBack/index.html.twig",[
            'piechart' => $pieChart,
            'reclamations' => $pagination,
            'query' => $query
        ]);
    }

    #[Route('/new', name: 'admin_reclamation_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ReclamationRepository $reclamationRepository): Response
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('admin_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamationBack/new.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_reclamation_show', methods: ['GET'])]
    public function show(Reclamation $reclamation): Response
    {
        return $this->render('reclamationBack/show.html.twig', [
            'reclamation' => $reclamation,
        ]);
    }

    #[Route('/{id}/edit', name: 'admin_reclamation_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $reclamationRepository->save($reclamation, true);

            return $this->redirectToRoute('admin_reclamation_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('reclamationBack/edit.html.twig', [
            'reclamation' => $reclamation,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'admin_reclamation_delete', methods: ['POST'])]
    public function delete(Request $request, Reclamation $reclamation, ReclamationRepository $reclamationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reclamation->getId(), $request->request->get('_token'))) {
            $reclamationRepository->remove($reclamation, true);
        }

        return $this->redirectToRoute('admin_reclamation_index', [], Response::HTTP_SEE_OTHER);
    }
    
    /**
     * @Route ("/printreclamation/{id}", name="print_reclamation", requirements={"id":"\d+"})
     */
    public function exportReclamationPDF($id, ReclamationRepository $repo)
    {
        // On définit les options du PDF
        $pdfOptions = new Options();
        // Police par défaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // On instancie Dompdf
        $dompdf = new Dompdf();
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);
        $reclamation = $repo->find($id);
        // dd($reclamations);

        // On génère le html
        $html = $this->renderView(
            'reclamationBack/print.html.twig',
            [
                'reclamation' => $reclamation
            ]
        );

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère un nom de fichier
        $fichier = 'reclamation'. $reclamation->getCin() . date('c') .'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier, [
            'Attachment' => true
        ]);

        return new Response();
    }

    /**
     * @Route ("/printallreclamations", name="print_reclamations", requirements={"id":"\d+"})
     */
    public function exportAllReclamationsPDF(ReclamationRepository $repo)
    {
        // On définit les options du PDF
        $pdfOptions = new Options();
        // Police par défaut
        $pdfOptions->set('defaultFont', 'Arial');
        $pdfOptions->setIsRemoteEnabled(true);

        // On instancie Dompdf
        $dompdf = new Dompdf($pdfOptions);
        $context = stream_context_create([
            'ssl' => [
                'verify_peer' => FALSE,
                'verify_peer_name' => FALSE,
                'allow_self_signed' => TRUE
            ]
        ]);
        $dompdf->setHttpContext($context);
        $reclamations = $repo->findAll();
        // dd($reclamations);

        // On génère le html
        $html = $this->renderView(
            'reclamation/printall.html.twig',
            [
                'reclamations' => $reclamations
            ]
        );

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // On génère un nom de fichier
        $fichier = 'reclamations'. date('c') .'.pdf';

        // On envoie le PDF au navigateur
        $dompdf->stream($fichier, [
            'Attachment' => true
        ]);

        return new Response();
    }
}

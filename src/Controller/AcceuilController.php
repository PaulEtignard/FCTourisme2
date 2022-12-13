<?php

namespace App\Controller;

use App\Repository\EtablissementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AcceuilController extends AbstractController
{
    private EtablissementRepository $etablissementRepository;

    /**
     * @param EtablissementRepository $etablissementRepository
     */
    public function __construct(EtablissementRepository $etablissementRepository)
    {
        $this->etablissementRepository = $etablissementRepository;
    }


    #[Route('/', name: 'app_acceuil')]
    public function index(): Response
    {

        $etablissements = $this->etablissementRepository->findBy([],["createdAt"=>"DESC"],4);


        return $this->render('acceuil/index.html.twig', [
            "Etablissements"=>$etablissements
        ]);
    }
}

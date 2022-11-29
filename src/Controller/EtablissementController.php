<?php

namespace App\Controller;

use App\Repository\EtablissementRepository;
use ContainerREZiCid\getKnpPaginatorService;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EtablissementController extends AbstractController
{
    private EtablissementRepository $etablissementRepository;

    /**
     * @param EtablissementRepository $etablissementRepository
     */
    public function __construct(EtablissementRepository $etablissementRepository)
    {
        $this->etablissementRepository = $etablissementRepository;
    }


    #[Route('/etablissements', name: 'app_etablissements')]
    public function allEtablissement(PaginatorInterface $paginator, Request $request): Response
    {

        $etablissements = $paginator->paginate(
            $this->etablissementRepository->findBy(["actif"=>'true'],['nom'=>'ASC']),
            $request->query->getInt("page",1),
            10
        );

        return $this->render('etablissement/index.html.twig', [
            'Etablissements' => $etablissements,
        ]);
    }
    #[Route('/etablissement/{slug}', name: 'app_etablissement_slug')]
    public function EtablissementSlug($slug): Response
    {
        $etablissement = $this->etablissementRepository->findOneBy(["slug"=>$slug]);
        return $this->render('etablissement/etablissement.html.twig', [
            "Etablissement" => $etablissement
        ]);


    }
}

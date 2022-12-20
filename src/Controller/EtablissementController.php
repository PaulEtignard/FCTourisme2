<?php

namespace App\Controller;

use App\Repository\CategorieRepository;
use App\Repository\EtablissementRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use GuzzleHttp\Client;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class EtablissementController extends AbstractController
{
    private EtablissementRepository $etablissementRepository;
    private UserRepository $userRepository;
    private HttpClientInterface $client;
    private CategorieRepository $categorieRepository;

    /**
     * @param EtablissementRepository $etablissementRepository
     */
    public function __construct(EtablissementRepository $etablissementRepository, CategorieRepository $categorieRepository, UserRepository $userRepository, HttpClientInterface $client)
    {
        $this->etablissementRepository = $etablissementRepository;
        $this->userRepository = $userRepository;
        $this->client = $client;
        $this->categorieRepository = $categorieRepository;
    }


    #[Route('/etablissements', name: 'app_etablissements')]
    public function allEtablissement(PaginatorInterface $paginator, Request $request): Response
    {
        $route = $request->attributes->get('_route');
        $etablissements = $paginator->paginate(
            $this->etablissementRepository->findBy(["actif"=>'true'],['nom'=>'ASC']),
            $request->query->getInt("page",1),
            12
        );
        return $this->render('etablissement/index.html.twig', [
            'Etablissements' => $etablissements,
            "ancienneRoute" =>$route
        ]);
    }

    #[Route('/etablissements/Tri/PlusRécent', name: 'app_etablissements_orderby_date_DESC')]
    public function allEtablissementOrderByDateDESC(PaginatorInterface $paginator, Request $request): Response
    {
        $etablissements = $paginator->paginate(
            $this->etablissementRepository->findBy(["actif"=>'true'],['createdAt'=>'desc']),
            $request->query->getInt("page",1),
            12
        );
        return $this->render('etablissement/index.html.twig', [
            'Etablissements' => $etablissements,
        ]);
    }
    #[Route('/etablissements/Tri/PlusVieux', name: 'app_etablissements_orderby_date_ASC')]
    public function allEtablissementOrderByDateASC(PaginatorInterface $paginator, Request $request): Response
    {
        $etablissements = $paginator->paginate(
            $this->etablissementRepository->findBy(["actif"=>'true'],['createdAt'=>'ASC']),
            $request->query->getInt("page",1),
            12
        );
        return $this->render('etablissement/index.html.twig', [
            'Etablissements' => $etablissements,
        ]);
    }
    #[Route('/etablissement/{slug}', name: 'app_etablissement_slug')]
    public function EtablissementSlug($slug): Response
    {
        $etablissement = $this->etablissementRepository->findOneBy(["slug"=>$slug]);
        $nomville = $etablissement->getVille()->getNom();
        return $this->render('etablissement/etablissement.html.twig', [
            "Etablissement" => $etablissement

        ]);
    }
    #[Route('/etablissement/{slug}/fav', name: 'app_etablissement_slug_fav')]
    public function AddFavoris($slug, EntityManagerInterface $manager): Response
    {
        $etablissement = $this->etablissementRepository->findOneBy(["slug"=>$slug]);

        $user = $this->userRepository->find($this->getUser());

        if (in_array($etablissement,$user->getEtablissementFavorits()->toArray())){
            $user->removeEtablissementFavorit($etablissement);
            $manager->persist($user);

            $etablissement->removeFavBy($user);
            $manager->persist($etablissement);
        } else {
            $user->addEtablissementFavorit($etablissement);
            $manager->persist($user);

            $etablissement->addFavBy($user);
            $manager->persist($etablissement);
        }
        $manager->flush();
        return $this->redirectToRoute("app_etablissements");
    }
    #[Route('/etablissements/favori', name: 'app_etablissement_fav')]
    public function EtablissementsFavoris(PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->userRepository->find($this->getUser());

        $Etablissements = $paginator->paginate(
            $user->getEtablissementFavorits(),
            $request->query->getInt("page",1),
            12
        );

        return $this->render('etablissement/index.html.twig', [
            "Etablissements" => $Etablissements
        ]);
    }
    #[Route('/etablissementdetail/{slug}/fav', name: 'app_etablissementdetail_slug_fav')]
    public function AddFavorisdetail($slug, EntityManagerInterface $manager): Response
    {
        $etablissement = $this->etablissementRepository->findOneBy(["slug"=>$slug]);
        $user = $this->userRepository->find($this->getUser());

        if (in_array($etablissement,$user->getEtablissementFavorits()->toArray())){
            $user->removeEtablissementFavorit($etablissement);
            $manager->persist($user);

            $etablissement->removeFavBy($user);
            $manager->persist($etablissement);
        } else {
            $user->addEtablissementFavorit($etablissement);
            $manager->persist($user);

            $etablissement->addFavBy($user);
            $manager->persist($etablissement);
        }
        $manager->flush();
        return $this->redirectToRoute("app_etablissement_slug",["slug"=>$slug]);
    }
    #[Route('/etablissements/{categorie}', name: 'app_etablissement_categorie')]
    public function Etablissementsrestaurant(PaginatorInterface $paginator, Request $request,$categorie): Response
    {
        $categorie = $this->categorieRepository->findOneBy(["nom"=>$categorie]);
        $listeEtablissement = $categorie->getEtablissements();
        $etablissements = [];
        foreach ($listeEtablissement as $etablissement ){
            if ($etablissement->isActif()){
                $etablissements[] = $etablissement;
            }
        }

        $Etablissements = $paginator->paginate(
            $etablissements,
            $request->query->getInt("page",1),
            12
        );

        return $this->render('etablissement/index.html.twig', [
            "Etablissements" => $Etablissements
        ]);
    }

}

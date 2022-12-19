<?php

namespace App\Controller;

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

    /**
     * @param EtablissementRepository $etablissementRepository
     */
    public function __construct(EtablissementRepository $etablissementRepository, UserRepository $userRepository, HttpClientInterface $client)
    {
        $this->etablissementRepository = $etablissementRepository;
        $this->userRepository = $userRepository;
        $this->client = $client;
    }


    #[Route('/etablissements', name: 'app_etablissements')]
    public function allEtablissement(PaginatorInterface $paginator, Request $request): Response
    {
        $etablissements = $paginator->paginate(
            $this->etablissementRepository->findBy(["actif"=>'true'],['nom'=>'ASC']),
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

        $response = $this->client->request(
            'GET',
            'http://api.openweathermap.org/geo/1.0/direct?q='.$nomville.'&appid=bc337001363c1789985b1a99d552587d'
        );

        $response = explode(",",$response->getContent());
        $reponse1 = explode(":",$response[1]);
        $lat = $reponse1[1];
        $reponse2 = explode(":",$response[2]);
        $long = $reponse2[1];

        $Request2 = $this->client->request(
            'GET',
            'https://api.openweathermap.org/data/2.5/weather?lat='.$lat.'&lon='.$long.'&lang=fr&appid=bc337001363c1789985b1a99d552587d'
        );

        $meteoexpode = explode(",",$Request2->getContent());

        $Temp = explode(":",$meteoexpode[4]);
        dd($meteoexpode);


        return $this->render('etablissement/etablissement.html.twig', [
            "Etablissement" => $etablissement,
            "temp"=>$Temp[1]
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
    #[Route('/etablissementFav/{slug}/fav', name: 'app_etablissementfav_slug_fav')]
    public function AddFavorisfav($slug, EntityManagerInterface $manager): Response
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
        return $this->redirectToRoute("app_etablissement_fav");
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

        return $this->render('etablissement/etablissementsFavori.html.twig', [
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

}

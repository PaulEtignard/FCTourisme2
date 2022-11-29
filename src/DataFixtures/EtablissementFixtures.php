<?php

namespace App\DataFixtures;

use App\Entity\Etablissement;
use App\Repository\CategorieRepository;
use App\Repository\VilleRepository;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\String\Slugger\SluggerInterface;

class EtablissementFixtures extends Fixture
{


    private VilleRepository $villeRepository;
    private SluggerInterface $slugger;
    private CategorieRepository $categorieRepository;

    /**
     * @param EtablissementFixtures $etablissementFixtures
     */
    public function __construct( SluggerInterface $slugger, VilleRepository $villeRepository, CategorieRepository $categorieRepository)
    {

        $this->slugger = $slugger;
        $this->villeRepository = $villeRepository;
        $this->categorieRepository = $categorieRepository;
    }


    public function load(ObjectManager $manager): void
    {
        $faker= Factory::create('fr_FR');
        $idMin = $this->villeRepository->findOneBy([],["id"=>"asc"]);
        $idmax = $this->villeRepository->findOneBy([],["id"=>"desc"]);
        $Catmin = $this->categorieRepository->findOneBy([],["id"=>"desc"]);
        $Catmax = $this->categorieRepository->findOneBy([],["id"=>"desc"]);


        for($i=1;$i<50;$i++){
            $etablissement = new Etablissement();
            $etablissement->setNom($faker->company)
                ->setAccueil($faker->boolean(50))
                ->setActif($faker->boolean(50))
                ->setAdresse($faker->address)
                ->setCreatedAt(New DateTimeImmutable())
                ->setDescription($faker->paragraphs($faker->numberBetween(1,3),true))
                ->setEmail($faker->email)
                ->setNumTel($faker->phoneNumber)
                ->setVille($this->villeRepository->findOneBy(["id"=>$faker->numberBetween($idMin->getId(),$idmax->getId())]))
                ->setSlug($this->slugger->slug($etablissement->getNom()))
                ->addCategorie($this->categorieRepository->findOneBy(["id"=>$faker->numberBetween($Catmin->getId(),$Catmax->getId())]));
            $manager->persist($etablissement);
        }
        $manager->flush();
    }
}

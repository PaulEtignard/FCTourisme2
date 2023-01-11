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
use Symfony\Component\Validator\Validator\ValidatorInterface;

class EtablissementFixtures extends Fixture
{


    private VilleRepository $villeRepository;
    private SluggerInterface $slugger;
    private CategorieRepository $categorieRepository;
    private ValidatorInterface $validator;

    /**
     * @param VilleRepository $villeRepository
     * @param SluggerInterface $slugger
     * @param CategorieRepository $categorieRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(VilleRepository $villeRepository, SluggerInterface $slugger, CategorieRepository $categorieRepository, ValidatorInterface $validator)
    {
        $this->villeRepository = $villeRepository;
        $this->slugger = $slugger;
        $this->categorieRepository = $categorieRepository;
        $this->validator = $validator;
    }


    public function load(ObjectManager $manager): void
    {
        $faker= Factory::create('fr_FR');


        $idMin = $this->villeRepository->findOneBy([],["id"=>"asc"]);
        $idmax = $this->villeRepository->findOneBy([],["id"=>"desc"]);
        $Catmin = $this->categorieRepository->findOneBy([],["id"=>"asc"]);
        $Catmax = $this->categorieRepository->findOneBy([],["id"=>"desc"]);


        for($i=1;$i<50;$i++){
            $nbcat = $faker->numberBetween(1,3);
            $nbcat1 = $faker->numberBetween($Catmin->getId(),$Catmax->getId());
            $nbcat2 = $faker->numberBetween($Catmin->getId(),$Catmax->getId());
            $nbcat3 = $faker->numberBetween($Catmin->getId(),$Catmax->getId());

            $etablissement = new Etablissement();
            $etablissement->setNom($faker->company)
                ->setAccueil($faker->boolean(50))
                ->setActif($faker->boolean(50))
                ->setAdresse($faker->address)
                ->setCreatedAt($faker->dateTimeBetween('-1 years'))
                ->setDescription($faker->paragraphs($faker->numberBetween(1,3),true))
                ->setEmail($faker->email)
                ->setNumTel($faker->phoneNumber)
                ->setVille($this->villeRepository->findOneBy(["id"=>$faker->numberBetween($idMin->getId(),$idmax->getId())]))
                ->setSlug($this->slugger->slug($etablissement->getNom()));
            if ($nbcat == 1){
                $etablissement->addCategorie($this->categorieRepository->findOneBy(["id"=>$nbcat1]));
            } elseif ($nbcat == 2){
                $etablissement->addCategorie($this->categorieRepository->findOneBy(["id"=>$nbcat1]))
                    ->addCategorie($this->categorieRepository->findOneBy(["id"=>$nbcat2]));
            } elseif ($nbcat==3){
                $etablissement->addCategorie($this->categorieRepository->findOneBy(["id"=>$nbcat1]))
                    ->addCategorie($this->categorieRepository->findOneBy(["id"=>$nbcat2]))
                    ->addCategorie($this->categorieRepository->findOneBy(["id"=>$nbcat3]));
            }

            $manager->persist($etablissement);
        }
        $manager->flush();
    }
}

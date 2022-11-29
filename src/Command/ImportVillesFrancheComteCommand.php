<?php

namespace App\Command;

use App\Entity\Ville;
use App\Repository\VilleRepository;
use Doctrine\ORM\EntityManagerInterface;
use League\Csv\Reader;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:import-villes-franche-comte',
    description: 'Add a short description for your command',
)]
class ImportVillesFrancheComteCommand extends Command
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function configure()
    {

    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $reader = Reader::createFromPath("src/Data/villes.csv");


        foreach ($reader as $row) {
            $villeExploded = explode(";", $row[0]);
            if ($villeExploded[1] <> "Commune") {
                if (in_array($villeExploded[3], [25, 39, 70, 90])) {
                    $ville = new Ville();
                    $ville->setNom($villeExploded[1]." ".$villeExploded[2])
                        ->setCodePostal($villeExploded[0])
                        ->setNomDepartement($villeExploded[4])
                        ->setNomRegion($villeExploded[5])
                        ->setNumDepartement($villeExploded[3]);
                    $this->em->persist($ville);
                }
            }
        }
        $this->em->flush();
        $io->success('Import effectué avec succes');

        return Command::SUCCESS;
    }
}

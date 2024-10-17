<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Contrat;
use App\Entity\ContratType;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Repository\ContratTypeRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ContratFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager): void
    {
        $now = new DateTime();
        $contratTypePool = $this->getReference(ContratTypeFixtures::CONTRAT_TYPE_POOL);


        $contrats = [];
        for ($i = 0; $i < 50; $i++) {
            $contrat = new Contrat();
            $contrat
                ->setName("contrat #" . $i)
                ->setCreatedAt($now)
                ->setUpdatedAt($now)
                ->setStartAt($now)
                ->setEndAt($now)
                ->setType($contratTypePool)
                ->setStatus("on")
            ;
            $manager->persist($contrat);
            $contrats[] = $contrat;
        }

        //Contrat Type > Contrats


        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ContratTypeFixtures::class,
        ];
    }
}

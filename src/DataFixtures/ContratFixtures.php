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


        $prefix = ContratTypeFixtures::CONTRAT_TYPE_POOL;
        $contratTypes = [];
        for ($i = ContratTypeFixtures::CONTRAT_TYPE_POOL_MIN; $i < ContratTypeFixtures::CONTRAT_TYPE_POOL_MAX; $i++) {
            $contratTypes[] = $prefix . $i;
        }



        $contrats = [];
        for ($i = 0; $i < 50; $i++) {
            $contratTypePool = $this->getReference($contratTypes[array_rand($contratTypes, 1)]);

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

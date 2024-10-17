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

    public const PREFIX = "contrat#";
    public const POOL_MIN = 0;
    public const POOL_MAX = 50;
    public function load(ObjectManager $manager): void
    {
        $now = new DateTime();

        $prefix = ContratTypeFixtures::PREFIX;
        $contratTypes = [];
        $isDone = false;

        for ($i = ContratTypeFixtures::POOL_MIN; $i < ContratTypeFixtures::POOL_MAX; $i++) {
            //Add prefix to contratTypes Array 
            $contratTypes[] = $prefix . $i;
        }

        $contrats = [];

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {



            //Pick a random contratType reference;
            $contratType = $this->getReference($contratTypes[array_rand($contratTypes, 1)]);

            $contrat = new Contrat();
            $contrat
                ->setName("contrat #" . $i)
                ->setDone($isDone)
                ->setCreatedAt($now)
                ->setUpdatedAt($now)
                ->setStartAt($now)
                ->setEndAt($now)
                ->setType($contratType)
                ->setStatus("on")
            ;
            $manager->persist($contrat);
            $this->addReference(self::PREFIX . $i, $contrat);
            $contrats[] = $contrat;
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ContratTypeFixtures::class,
        ];
    }
}

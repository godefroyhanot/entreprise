<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\ContratType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ContratTypeFixtures extends Fixture
{


    public const PREFIX = "contratType#";
    public const POOL_MAX = 5;
    public const POOL_MIN = 0;
    public function load(ObjectManager $manager): void
    {


        $now = new DateTime();
        $contratTypes = [];
        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $contratType = new ContratType();
            $contratType
                ->setName("contrat Type #" . $i)
                ->setCreatedAt($now)
                ->setUpdatedAt($now)
                ->setStatus("on")
            ;
            $manager->persist($contratType);
            $this->addReference(self::PREFIX . $i, $contratType);

            $contratTypes[] = $contratType;
        }



        $manager->flush();
    }
}

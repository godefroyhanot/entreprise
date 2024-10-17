<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\ContratType;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ContratTypeFixtures extends Fixture
{


    public const CONTRAT_TYPE_POOL = 'contrat-type';
    public function load(ObjectManager $manager): void
    {


        $now = new DateTime();
        $contratTypes = [];
        for ($i = 0; $i < 5; $i++) {
            $contratType = new ContratType();
            $contratType
                ->setName("contrat Type #" . $i)
                ->setCreatedAt($now)
                ->setUpdatedAt($now)
                ->setStatus("on")
            ;
            $manager->persist($contratType);
            $contratTypes[] = $contratType;
        }


        $manager->flush();
        $this->addReference(self::CONTRAT_TYPE_POOL, $contratType);
    }
}

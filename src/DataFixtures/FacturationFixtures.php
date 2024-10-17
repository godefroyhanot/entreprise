<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Facturation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class FacturationFixtures extends Fixture
{
    public const PREFIX = "facturation#";
    public const POOL_MAX = 5;
    public const POOL_MIN = 0;

    public function load(ObjectManager $manager): void
    {
        $now = new DateTime();
        $facturations = [];

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $facturation = new Facturation();
            $facturation
                ->setNumber("Facture #" . $i)
                ->setClient("Client #" . $i)
                ->setContrat("Contrat #" . $i)
                ->setCreatedAt($now)
                ->setUpdateAt($now)
                ->setStatus(1);

            $manager->persist($facturation);
            $this->addReference(self::PREFIX . $i, $facturation);

            $facturations[] = $facturation;
        }

        $manager->flush();
    }
}

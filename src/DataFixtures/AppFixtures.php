<?php

namespace App\DataFixtures;

use App\Entity\Client;
use App\Entity\Contrat;
use App\Entity\ContratType;
use DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $now = new DateTime();

        //Clients > Contrats
        /* Todo : Retirer ce bout de code et l'implementer dans un fixture adéquat*/
        // for ($i = 0; $i < 15; $i++) {
        //     $client = new Client();
        //     $client
        //         ->setName("client #" . $i)
        //         ->setCreatedAt($now)
        //         ->setUpdatedAt($now)
        //         ->setStatus("on")
        //     ;
        //     $manager->persist($client);
        // }

        $manager->flush();
    }
}

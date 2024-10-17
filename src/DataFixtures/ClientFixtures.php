<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ClientFixtures extends Fixture
{
    public const PREFIX = "client#";
    public const POOL_MAX = 10;
    public const POOL_MIN = 0;

    public function load(ObjectManager $manager): void
    {
        $now = new DateTime();
        $clients = [];

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $client = new Client();
            $client
                ->setName("Client #" . $i)
                ->setCreatedAt($now)
                ->setUpdatedAt($now)
                ->setStatus("active"); // ou autre statut selon ce que tu veux tester

            $manager->persist($client);
            $this->addReference(self::PREFIX . $i, $client);

            $clients[] = $client;
        }

        $manager->flush();
    }
}
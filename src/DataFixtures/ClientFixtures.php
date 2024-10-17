<?php

namespace App\DataFixtures;

use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory;
use Faker\Generator; // Import du type pour Faker

class ClientFixtures extends Fixture
{
    public const PREFIX = "client#";
    public const POOL_MAX = 10;
    public const POOL_MIN = 0;

    private Generator $faker; 

    public function __construct()
    {
        $this->faker = Factory::create(); 
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $client = new Client();
            $client
                ->setName("Client #" . $i)
                ->setCreatedAt($now)
                ->setUpdatedAt($now)
                ->setStatus("on"); // ou autre statut selon ce que tu veux tester

            $manager->persist($client);
            $this->addReference(self::PREFIX . $i, $client); 
        }

        $manager->flush();
    }
}

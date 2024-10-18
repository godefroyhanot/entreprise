<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Client;
use App\Entity\Facturation;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class FacturationFixtures extends Fixture
{


    private Generator $faker;

    public const PREFIX = "facturation#";
    public const POOL_MAX = 5;
    public const POOL_MIN = 0;

    public function __construct()
    {
        $this->faker = Factory::create(); // Initialisation de Faker dans le constructeur
    }
    public function load(ObjectManager $manager): void
    {
        // Instanciation de Faker

        $facturations = [];

        $clients = $manager->getRepository(Client::class)->findAll();

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $facturation = new Facturation();

            $facturation
                ->setNumber($this->faker->unique()->numerify('Facture ###'))
                ->setClient($clients[array_rand($clients, 1)])
                ->setContrat($this->faker->company)
                ->setCreatedAt($this->faker->dateTimeThisYear)
                ->setUpdateAt($this->faker->dateTimeThisYear)
                ->setStatus($this->faker->randomElement([0, 1]));


            $manager->persist($facturation);
            $this->addReference(self::PREFIX . $i, $facturation);

            $facturations[] = $facturation;
        }

        $manager->flush();
    }
}

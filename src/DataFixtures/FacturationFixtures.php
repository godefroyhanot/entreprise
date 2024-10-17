<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Facturation;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class FacturationFixtures extends Fixture
{
    public const PREFIX = "facturation#";
    public const POOL_MAX = 5;
    public const POOL_MIN = 0;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $facturations = [];

        $clients = $manager->getRepository(Client::class)->findAll();

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $facturation = new Facturation();
            $facturation
                ->setNumber("Facture #" . $i)
                ->setClient($faker->randomElement($clients))
                ->setContrat($faker->company)
                ->setCreatedAt($faker->dateTimeThisYear)
                ->setUpdateAt($faker->dateTimeThisYear)
                ->setStatus($faker->randomElement(['on', 'off']));
            $manager->persist($facturation);
            $this->addReference(self::PREFIX . $i, $facturation);

            $facturations[] = $facturation;
        }

        $manager->flush();
    }
}  
<?php

namespace App\DataFixtures;

use Faker\Factory;
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
        // Instanciation de Faker
        $faker = Factory::create('fr_FR');

        $facturations = [];

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $facturation = new Facturation();
            $facturation
                ->setNumber($faker->unique()->numerify('Facture ###'))
                ->setClient($faker->name)
                ->setContrat($faker->company)
                ->setCreatedAt($faker->dateTimeThisYear)
                ->setUpdateAt($faker->dateTimeThisYear)
                ->setStatus($faker->randomElement([0, 1]));


            $manager->persist($facturation);
            $this->addReference(self::PREFIX . $i, $facturation);

            $facturations[] = $facturation;
        }

        $manager->flush();
    }
}

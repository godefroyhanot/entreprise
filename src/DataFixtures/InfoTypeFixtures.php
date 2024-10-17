<?php

namespace App\DataFixtures;

use App\Entity\InfoType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class InfoTypeFixtures extends Fixture
{
    public const PREFIX = 'infotype#';
    public const POOL_MAX = 5;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 0; $i < self::POOL_MAX; $i++) {
            $infoType = new InfoType();
            $infoType->setName('Type ' . $i)
                     ->setInfo($faker->sentence(10)) // Générer une description courte
                     ->setCreatedAt($faker->dateTimeThisYear()) // Date aléatoire cette année
                     ->setUpdatedAt($faker->dateTimeThisYear()) // Date de mise à jour aléatoire
                     ->setStatus($faker->randomElement(['active', 'inactive', 'pending'])); // Statut aléatoire

            $manager->persist($infoType);
            $this->addReference(self::PREFIX . $i, $infoType); // Ajouter une référence pour utilisation dans d'autres fixtures
        }

        $manager->flush();
    }
}

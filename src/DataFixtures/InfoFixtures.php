<?php

namespace App\DataFixtures;

use App\Entity\Info;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class InfoFixtures extends Fixture
{
    public const PREFIX = 'info#';
    public const POOL_MAX = 10;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        // Générer des infos et les associer à un InfoType existant
        for ($i = 0; $i < self::POOL_MAX; $i++) {
            $info = new Info();
            $info->setAnonymous($faker->boolean())
                 ->setType($this->getReference('infotype#' . $faker->numberBetween(0, 4))) // Associer à un InfoType (référence à créer)
                 ->setInfo($faker->text(200))
                 ->setCreatedAt($faker->dateTimeThisYear())
                 ->setUpdatedAt($faker->dateTimeThisYear())
                 ->setStatus($faker->randomElement(['active', 'inactive', 'pending']));

            $manager->persist($info);
            $this->addReference(self::PREFIX . $i, $info); // Ajouter une référence pour réutilisation
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            InfoTypeFixtures::class, // Assure que InfoType est chargé avant
        ];
    }
}
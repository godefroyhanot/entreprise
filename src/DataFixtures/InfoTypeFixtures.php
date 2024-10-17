<?php

namespace App\DataFixtures;

use App\Entity\InfoType;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class InfoTypeFixtures extends Fixture
{
    public const PREFIX = 'infotype#';
    public const POOL_MAX = 5;

    private Generator $faker; // Déclaration du type Generator pour Faker

    public function __construct()
    {
        $this->faker = Factory::create(); // Initialisation de Faker dans le constructeur
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < self::POOL_MAX; $i++) {
            $infoType = new InfoType();
            $infoType->setName('Type ' . $i)
                     ->setInfo($this->faker->sentence(10)) // Utilition de Faker via $this->faker
                     ->setCreatedAt($this->faker->dateTimeThisYear()) 
                     ->setUpdatedAt($this->faker->dateTimeThisYear()) 
                     ->setStatus($this->faker->randomElement(['active', 'inactive', 'pending'])); 

            $manager->persist($infoType);
            $this->addReference(self::PREFIX . $i, $infoType); // Ajout de la référence
        }

        $manager->flush();
    }
}

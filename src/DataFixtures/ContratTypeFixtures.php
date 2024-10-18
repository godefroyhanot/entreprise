<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\ContratType;
use Doctrine\Persistence\ObjectManager;
use Faker\Generator;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ContratTypeFixtures extends Fixture
{


    public const PREFIX = "contratType#";
    public const POOL_MAX = 5;
    public const POOL_MIN = 0;

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }
    public function load(ObjectManager $manager): void
    {


        $now = new DateTime();
        $contratTypes = [];
        $dateCreated = $this->faker->dateTimeInInterval('-1 week', '+1 week');
        $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $contratType = new ContratType();
            $contratType
                ->setName($this->faker->lexify('type-??????????'))
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStatus("on")
            ;
            $manager->persist($contratType);
            $this->addReference(self::PREFIX . $i, $contratType);

            $contratTypes[] = $contratType;
        }



        $manager->flush();
    }
}

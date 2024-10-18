<?php

namespace App\DataFixtures;

use App\Entity\QuantityType;
use DateTime;
use Faker\Factory;
use Faker\Generator;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class QuantityTypeFixtures extends Fixture
{


    public const PREFIX = "quantityType#";
    public const POOL_MAX = 5;
    public const POOL_MIN = 0;


    /**
     * @var Generator
     */
    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }


    public function load(ObjectManager $manager): void
    {

        $quantityNames = ['unité', 'g', 'j', 'm', 'h'];
        $now = new DateTime();
        $quantityTypes = [];
        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {


            $dateCreated = $this->faker->dateTimeInInterval('-1 week', '+1 week');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
            $quantityType = new QuantityType();
            $quantityType
                ->setName($quantityNames[$i])
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStatus("on")
            ;
            $manager->persist($quantityType);
            $this->addReference(self::PREFIX . $i, $quantityType);

            $quantityTypes[] = $quantityType;
        }



        $manager->flush();
    }
}

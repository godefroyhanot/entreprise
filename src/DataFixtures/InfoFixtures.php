<?php

namespace App\DataFixtures;

use App\Entity\Info;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;

class InfoFixtures extends Fixture implements DependentFixtureInterface
{
    public const PREFIX = 'info#';
    public const POOL_MAX = 10;
    public const POOL_MIN = 0;

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create(); // Initialisation de Faker dans le constructeur
    }

    public function load(ObjectManager $manager): void
    {
        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $info = new Info();
            $info->setAnonymous($this->faker->boolean())
                ->setType($this->getReference(InfoTypeFixtures::PREFIX . $this->faker->numberBetween(InfoTypeFixtures::POOL_MIN, InfoTypeFixtures::POOL_MIN))) // Associer à un InfoType
                ->setInfo($this->faker->text(200))
                ->setCreatedAt($this->faker->dateTimeThisYear())
                ->setUpdatedAt($this->faker->dateTimeThisYear())
                ->setStatus($this->faker->randomElement(['on', 'off']));

            $manager->persist($info);
            $this->addReference(self::PREFIX . $i, $info); // Ajouter une référence
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            InfoTypeFixtures::class, // Dépendance à InfoTypeFixtures
        ];
    }
}

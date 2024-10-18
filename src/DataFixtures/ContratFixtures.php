<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Contrat;
use App\Entity\ContratType;
use App\Entity\Client;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use App\Repository\ContratTypeRepository;
use Faker\Generator;
use Faker\Factory;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ContratFixtures extends Fixture implements DependentFixtureInterface
{

    public const PREFIX = "contrat#";
    public const POOL_MIN = 0;
    public const POOL_MAX = 50;

    private Generator $faker;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {
        $now = new DateTime();

        $prefixContrat = ContratTypeFixtures::PREFIX;
        $contratTypes = [];

        $prefixClient = ClientFixtures::PREFIX;
        $clientTypes = [];

        $prefixFacture = FacturationFixtures::PREFIX;
        $factureTypes = [];

        $prefixProduct = ProductFixtures::PREFIX;
        $productTypes = [];



        $isDone = false;

        for ($i = ContratTypeFixtures::POOL_MIN; $i < ContratTypeFixtures::POOL_MAX; $i++) {
            //Add prefix to contratTypes Array 
            $contratTypes[] = $prefixContrat . $i;
        }
        for ($i = ClientFixtures::POOL_MIN; $i < ClientFixtures::POOL_MAX; $i++) {
            //Add prefix to contratTypes Array 
            $clientTypes[] = $prefixClient . $i;
        }
        for ($i = FacturationFixtures::POOL_MIN; $i < FacturationFixtures::POOL_MAX; $i++) {
            //Add prefix to factureTypes Array 
            $factureTypes[] = $prefixFacture . $i;

        }
        for ($i = ProductFixtures::POOL_MIN; $i < ProductFixtures::POOL_MAX; $i++) {
            //Add prefix to ProductTypes Array 
            $productTypes[] = $prefixProduct . $i;
        }

        for ($i = ProductFixtures::POOL_MIN; $i < ProductFixtures::POOL_MAX; $i++) {
            //Add prefix to ProductTypes Array 
            $productTypes[] = $prefixProduct . $i;
        }

        $contrats = [];

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {



            //Pick a random contratType reference;
            $contratType = $this->getReference($contratTypes[array_rand($contratTypes, 1)]);
            //Pick a random clientType reference;
            $clientType = $this->getReference($clientTypes[array_rand($clientTypes, 1)]);
            //Pick a random factureType reference;
            $factureType = $this->getReference($factureTypes[array_rand($factureTypes, 1)]);
            //Pick a random ProductType reference;
            $productType = $this->getReference($productTypes[array_rand($productTypes, 1)]);





            $dateCreated = $this->faker->dateTimeInInterval('-1 week', '+1 week');
            $dateStarted = $this->faker->dateTimeInInterval('-1 year', '+1 year');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
            $dateEnd = $this->faker->dateTimeBetween($dateStarted, $now);
            $contrat = new Contrat();
            $contrat
                ->setName($this->faker->numerify('contrat-####'))
                ->setDone($isDone)
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStartAt($dateStarted)
                ->setEndAt($dateEnd)
                ->setType($contratType)
                ->setClient($clientType)
                ->addFacture($factureType)
                ->addProduct($productType)

                ->setStatus("on")
            ;
            $manager->persist($contrat);
            $this->addReference(self::PREFIX . $i, $contrat);
            $contrats[] = $contrat;
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            ContratTypeFixtures::class,
            ProductFixtures::class,
            ClientFixtures::class,
            FacturationFixtures::class,
        ];
    }
}
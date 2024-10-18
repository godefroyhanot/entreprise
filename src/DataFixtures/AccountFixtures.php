<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Account;
use App\DataFixtures\ClientFixtures;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class AccountFixtures extends Fixture implements DependentFixtureInterface
{

    public const PREFIX = "account#";
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
        $prefixClient = ClientFixtures::PREFIX;

        $clients = [];
        for ($i = ClientFixtures::POOL_MIN; $i < ClientFixtures::POOL_MAX; $i++) {
            $clients[] = $prefixClient . $i;
        }


        $now = new DateTime();
        $accounts = [];
        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {

            $client = $this->getReference($clients[array_rand($clients, 1)]);



            $dateCreated = $this->faker->dateTimeInInterval('-1 week', '+1 week');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
            $account = new Account();
            $account
                ->setName($this->faker->email())
                ->setUrl($this->faker->url())
                ->setPassword($this->faker->password())
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setClient($client)
                ->setStatus("on")
            ;
            $manager->persist($account);
            $this->addReference(self::PREFIX . $i, $account);

            $accounts[] = $account;
        }



        $manager->flush();
    }


    public function getDependencies()
    {
        return [
            ClientFixtures::class,
        ];
    }
}

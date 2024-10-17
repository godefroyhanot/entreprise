<?php

namespace App\DataFixtures;

use DateTime;
use Faker\Factory;
use Faker\Generator;
use App\Entity\Account;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class AccountFixtures extends Fixture
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


        $now = new DateTime();
        $accounts = [];
        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {


            $dateCreated = $this->faker->dateTimeInInterval('-1 week', '+1 week');
            $dateUpdated = $this->faker->dateTimeBetween($dateCreated, $now);
            $account = new Account();
            $account
                ->setName($this->faker->email())
                ->setUrl($this->faker->url())
                ->setPassword($this->faker->password())
                ->setCreatedAt($dateCreated)
                ->setUpdatedAt($dateUpdated)
                ->setStatus("on")
            ;
            $manager->persist($account);
            $this->addReference(self::PREFIX . $i, $account);

            $accounts[] = $account;
        }



        $manager->flush();
    }
}

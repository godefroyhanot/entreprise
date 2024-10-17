<?php

namespace App\DataFixtures;

use DateTime;
use App\Entity\Client;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Faker\Factory; 

class ClientFixtures extends Fixture
{
    public const PREFIX = "client#";
    public const POOL_MAX = 10;
    public const POOL_MIN = 0;

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create(); 

        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $client = new Client();
            $client
                ->setName($faker->company)
                ->setCreatedAt($faker->dateTimeThisYear())
                ->setUpdatedAt($faker->dateTimeThisYear()) 
                ->setStatus($faker->randomElement(['active', 'inactive', 'pending'])); 
            $manager->persist($client);
            $this->addReference(self::PREFIX . $i, $client); 
        }

        $manager->flush();
    }
}

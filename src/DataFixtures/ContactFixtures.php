<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Contact;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ContactFixtures extends Fixture implements DependentFixtureInterface
{
    public const PREFIX = "contact#";
    public const POOL_MAX = 10;
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

        $contactsData = [];
        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $client = $this->getReference($clients[array_rand($clients, 1)]);
            $contact = new Contact();
            $contact
                ->setName($this->faker->firstName())
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setStatus('on');
            $manager->persist($contact);
            $this->addReference(self::PREFIX . $i, $contact);
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

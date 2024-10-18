<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class ProductFixtures extends Fixture implements DependentFixtureInterface
{

    public const PREFIX = "productType#";
    public const POOL_MAX = 5;
    public const POOL_MIN = 0;



    private Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {

        $prefixQuantityType = QuantityTypeFixtures::PREFIX;
        $quantityTypes = [];
        for ($i = QuantityTypeFixtures::POOL_MIN; $i < QuantityTypeFixtures::POOL_MAX; $i++) {
            //Add prefix to contratTypes Array 
            $quantityTypes[] = $prefixQuantityType . $i;
        }

        for ($i = self::POOL_MIN; $i <= self::POOL_MAX; $i++) {
            $quantityType = $this->getReference($quantityTypes[array_rand($quantityTypes, 1)]);
            $product = new Product();
            $product->setStatus('on');
            $product->setCreatedAt($this->faker->dateTimeThisYear());
            $product->setUpdatedAt($this->faker->dateTimeThisMonth());
            $product->setQuantity($this->faker->numberBetween(0, 10));
            $product->setQuantityType($quantityType);
            $manager->persist($product);
            $this->addReference(self::PREFIX . $i, $product);
        }

        $manager->flush();
    }


    public function getDependencies()
    {
        return [
            QuantityTypeFixtures::class,
        ];
    }
}


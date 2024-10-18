<?php

namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Product;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;

class ProductFixtures extends Fixture
{

    public const PREFIX = "ProductType#";
    public const POOL_MAX = 5;
    public const POOL_MIN = 0;


    private Generator $faker;
    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager): void
    {


        for ($i = 1; $i <= 10; $i++) {
            $product = new Product();
            $product->setStatus('on');
            $product->setCreatedAt($this->faker->dateTimeThisYear());
            $product->setUpdatedAt($this->faker->dateTimeThisMonth());

            $manager->persist($product);
        }

        $manager->flush();
    }

}
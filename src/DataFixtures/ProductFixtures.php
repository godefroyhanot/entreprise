<?php

namespace App\DataFixtures;

use App\Entity\Product;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ProductFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create();

        for ($i = 1; $i <= 10; $i++) {
            $product = new Product();
            $product->setStatus('ON'); 
            $product->setCreatedAt($faker->dateTimeThisYear());
            $product->setUpdatedAt($faker->dateTimeThisMonth());

            $manager->persist($product);
        }

        $manager->flush();
    }
}

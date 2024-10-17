<?php

namespace App\DataFixtures;

use App\Entity\Contact; 
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {

        for ($i = 1; $i <= 10; $i++) { 
            $contact = new Contact();
            $contact
                ->setName("Contact #" . $i) 
                ->setCreatedAt(new \DateTime()) 
                ->setUpdatedAt(new \DateTime()) 
                ->setStatus($i % 2 == 0 ? 'active' : 'inactive'); 
            
            $manager->persist($contact); 
        }

        $manager->flush(); 
    }
}

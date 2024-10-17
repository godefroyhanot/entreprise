<?php

namespace App\DataFixtures;

use App\Entity\Contact; 
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        $contactsData = [];

        foreach ($contactsData as $data) {
            $contact = new Contact();
            $contact
                ->setName($data[0])
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setStatus($data[1]);
            
            
            $manager->persist($contact);
        }

        $manager->flush();
    }
}

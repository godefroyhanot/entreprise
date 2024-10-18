<?php

namespace App\DataFixtures;

use App\Entity\ContactLink;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use DateTime;

class ContactLinkFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $now = new DateTime();
        for ($i = 1; $i <= 10; $i++) { 
            $contactLink = new ContactLink();
            $contactLink->setValue("http://example.com/contact-link-" . $i) 
                        ->setCreatedAt($now) // Date actuelle
                        ->setUpdatedAt($now) // Date actuelle
                        ->setStatus($i % 2 == 0 ? 'on' : 'off'); 
            
            $manager->persist($contactLink); 

        }

        $manager->flush();
    }
}

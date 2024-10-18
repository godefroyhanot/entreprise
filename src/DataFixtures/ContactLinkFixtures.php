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
        $contactLinksData = [];

        $now = new DateTime();

        foreach ($contactLinksData as $data) {
            $contactLink = new ContactLink();
            $contactLink->setValue($data[0])
                        ->setCreatedAt($now)
                        ->setUpdatedAt($now)
                        ->setStatus($data[1]);
            
            $manager->persist($contactLink);
        }

        $manager->flush();
    }
}

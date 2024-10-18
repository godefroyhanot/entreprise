<?php

namespace App\DataFixtures;

use App\Entity\Contact;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ContactFixtures extends Fixture
{
    public const PREFIX = "contact#";
    public const POOL_MAX = 10;
    public const POOL_MIN = 0;
    public function load(ObjectManager $manager): void
    {


        $contactsData = [];
        for ($i = self::POOL_MIN; $i < self::POOL_MAX; $i++) {
            $contact = new Contact();
            $contact
                ->setName(self::PREFIX . $i)
                ->setCreatedAt(new \DateTime())
                ->setUpdatedAt(new \DateTime())
                ->setStatus('on');
            $manager->persist($contact);
            $this->addReference(self::PREFIX . $i, $contact);

        }

        $manager->flush(); 
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\Job;
use App\Entity\Person;
use DateTime;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        
        // Create 20 Person
        for ($i = 0; $i < 20; $i++) {
            $person = new Person;
            $person->setName('Prenom ' . $i);
            $person->setFirstname('Nom ' . $i);
            $person->setBirthdate(new DateTime('01/01/2000'));
            $manager->persist($person);

            $this->setReference("person".$i, $person);
        }

         // Create 20 Job
         for ($i = 0; $i < 20; $i++) {
            $job = new Job;
            
            $companyNames = array("companyName1",
                "companyName2",
                 "companyName3");
            $job->setCompanyName($companyNames[rand(0,2)]);
            $job->setPosition('position' . $i);
            $job->setStartedAt(new DateTimeImmutable());
            $job->setStopedAt(new DateTimeImmutable());
            $job->setPerson($this->getReference('person'.rand(0,19)));
            $manager->persist($job);
        }

        $manager->flush();
    }
}

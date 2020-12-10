<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\Expertise;
use Faker;

class ExpertiseFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 30; $i++) {
            $expertise = new Expertise();
            $expertise->setName($faker->words(3, true));
            $manager->persist($expertise);
            $this->addReference('expertise_' . $i, $expertise);
        }
        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use App\Entity\Service;
use Faker;

class ServiceFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 30; $i++) {
            $service = new Service();
            $service->setStartDate($faker->dateTimeBetween($startDate = 'now', '+2 years'));
            $service->setEndDate($faker->dateTimeBetween($startDate, '+2 days'));
            $service->setStructureName($faker->words(2, true));
            $service->setTrainingCentreNumber($faker->randomNumber(9));
            $service->setTitle($faker->words(4, true));
            $service->setDescription($faker->paragraph());
            $service->setPlacesNumber(rand(1, 100));
            $service->setPrice(rand(0, 1000));
            $service->setTown($faker->city());
            $service->setZipcode($faker->randomNumber(5));
            $service->setAddress($faker->address());
            $service->setTrainer($faker->name());
            $service->setIsValidated($faker->boolean());
            $service->setExpertise($this->getReference('expertise_' . rand(0, 29)));
            $service->setUser($this->getReference('user_' . rand(0, 9)));
            $service->setTypeService($this->getReference('typeService_' . rand(0, 3)));
            $service->setFormatService($this->getReference('formatService_' . rand(0, 1)));
            $manager->persist($service);
            $this->addReference('service_' . $i, $service);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return array (
            UserFixtures::class,
            TypeServiceFixtures::class,
            FormatServiceFixtures::class,
            ExpertiseFixtures::class
        );
    }
}

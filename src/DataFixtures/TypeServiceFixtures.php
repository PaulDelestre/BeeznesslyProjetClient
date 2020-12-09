<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\TypeService;

class TypeServiceFixtures extends Fixture
{
    protected const TYPESERVICES = [
        'Workshop',
        'Atelier',
        'Conférence',
        'Formation',
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::TYPESERVICES as $key => $type) {
            $typeService = new TypeService();
            $typeService->setName($type);
            $manager->persist($typeService);
            $this->addReference('typeService_' . $key, $typeService);
        }

        $manager->flush();
    }
}

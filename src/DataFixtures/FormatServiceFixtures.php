<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\FormatService;

class FormatServiceFixtures extends Fixture
{
    protected const FORMATSERVICE = [
        'Collectif',
        'Individuel'
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::FORMATSERVICE as $key => $format) {
            $formatService = new FormatService();
            $formatService->setName($format);
            $manager->persist($formatService);
            $this->addReference('formatService_' . $key, $formatService);
        }
        $manager->flush();
    }
}

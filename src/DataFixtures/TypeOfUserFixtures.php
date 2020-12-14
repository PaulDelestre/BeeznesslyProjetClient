<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use App\Entity\TypeOfUser;

class TypeOfUserFixtures extends Fixture
{
    protected const TYPEOFUSER = [
        'Expert',
        'Entrepreneur'
    ];
    public function load(ObjectManager $manager)
    {
        foreach (self::TYPEOFUSER as $key => $type) {
            $typeOfUser = new TypeOfUser();
            $typeOfUser->setName($type);
            $manager->persist($typeOfUser);
            $this->addReference('typeOfUser_' . $key, $typeOfUser);
        }
        $manager->flush();
    }
}

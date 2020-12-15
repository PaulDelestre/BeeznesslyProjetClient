<?php

namespace App\DataFixtures;

use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Entity\User;
use Faker;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setEmail('user' . $i . '@expert.com');
            $user->setPassword($this->encoder->encodePassword($user, 'password'));
            $user->setRoles(['ROLE_EXPERT']);
            $user->setDescription($faker->paragraph());
            $user->setPhone($faker->randomNumber(9));
            $user->setCompanyName($faker->words(2, true));
            $user->setSiretNumber($faker->randomNumber(9));
            $user->setIsValidated($faker->boolean());
            $user->setTown($faker->city());
            $user->setZipcode($faker->randomNumber(5));
            $user->setAdress($faker->address());
            for ($j = 0; $j < 3; $j++) {
                $user->addExpertise($this->getReference('expertise_' . rand(0, 5)));
            }
            $user->setTypeOfUser($this->getReference('typeOfUser_0'));
            $user->setProvider($this->getReference('provider_' . rand(0, 3)));
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }
        for ($i = 10; $i < 11; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setEmail('user' . $i . '@admin.com');
            $user->setPassword($this->encoder->encodePassword($user, 'password'));
            $user->setRoles(['ROLE_ADMIN']);
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }
        for ($i = 11; $i < 14; $i++) {
            $user = new User();
            $user->setFirstname($faker->firstName());
            $user->setLastname($faker->lastName());
            $user->setEmail('user' . $i . '@entrepreneur.com');
            $user->setPassword($this->encoder->encodePassword($user, 'password'));
            $user->setRoles(['ROLE_ENTREPRENEUR']);
            $user->setPhone($faker->randomNumber(9));
            $user->setTypeOfUser($this->getReference('typeOfUser_1'));
            $manager->persist($user);
            $this->addReference('user_' . $i, $user);
        }
        $manager->flush();
    }
    public function getDependencies()
    {
        return array (
            ProviderFixtures::class,
            ExpertiseFixtures::class,
            TypeOfUserFixtures::class
        );
    }
}

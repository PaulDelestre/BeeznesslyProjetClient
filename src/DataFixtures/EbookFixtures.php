<?php

namespace App\DataFixtures;

use Faker;
use DateTime;
use App\Entity\Ebook;
use App\Service\SlugifyService;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EbookFixtures extends Fixture implements DependentFixtureInterface
{
    private $slugifyService;

    public function __construct(SlugifyService $slugifyService)
    {
        $this->slugifyService = $slugifyService;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for ($i = 0; $i < 50; $i++) {
            $ebook = new Ebook();
            $ebook->setTitle($faker->words(3, true));
            $ebook->setDescription($faker->paragraph());
            $ebook->setReleaseDate(new \DateTime($faker->date('Y-m-d', 'now')));
            $ebook->setEditorName($faker->word());
            $ebook->setAuthor($faker->name());
            $ebook->setIsValidated($faker->numberBetween(0, 1));
            $ebook->setSlug($this->slugifyService->generate($ebook->getTitle()));
            $ebook->setExpertise($this->getReference('expertise_' . rand(0, 5)));
            $ebook->setUser($this->getReference('user_' . rand(0, 9)));
            $manager->persist($ebook);
            $this->addReference('ebook_' . $i, $ebook);
        }

        $manager->flush();
    }
    public function getDependencies()
    {
        return array (
            UserFixtures::class,
            ExpertiseFixtures::class,
        );
    }
}

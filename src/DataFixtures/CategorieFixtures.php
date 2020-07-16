<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategorieFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        // Instanciation de faker
        $faker = Factory::create('fr_FR');

        // Créer 10 categories
        for ($i = 0; $i < 10; $i++) {
            $categorie = new Categorie();
            $categorie
                ->setNom($faker->realText(15))
            ;
            $manager->persist($categorie);
            // Lier la référence ($reference) à l'entité ($categorie), pour la récupérer dans d'autres fixtures
            $reference = 'categorie_' . $i;
            $this->addReference($reference,$categorie);
        }

        $manager->flush();
    }
}

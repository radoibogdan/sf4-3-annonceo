<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Categorie;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class AnnonceFixtures extends Fixture implements DependentFixtureInterface
{

    public function load(ObjectManager $manager)
    {
        // Instanciation de faker
        $faker = Factory::create('fr_FR');

        // Créer 20 annonces
        for ($i = 0; $i < 20; $i++) {
            $annonce = new Annonce();
            $annonce
                ->setTitre($faker->sentence(5))
                ->setDescription($faker->realText(20))
                ->setPrix($faker->numberBetween(1000, 10000))
                ->setVille($faker->city)
                ->setCodePostal($faker->postcode)
                ->setAdresse($faker->address)
                ->setCreation($faker->dateTimeBetween('-6 months'))
            ;
            // Récupération aléatoire d'une catégorie par une référence
            $categorieReference = 'categorie_' . $faker->numberBetween(0, 9);
            /** @var Categorie $categorie */
            $categorie = $this->getReference($categorieReference); // getReference -> renvoie une entité Categorie
            $annonce->setCategorie($categorie);

            // Récupération aléatoire d'un user par une référence
            $userReference = 'user_' . $faker->numberBetween(0, 9);
            /** @var User $user */
            $user = $this->getReference($userReference); // getReference -> renvoie une entité user
            $annonce->setAuteur($user);
            $manager->persist($annonce);

            // Lier la référence ($reference) à l'entité ($annonce), pour la récupérer dans d'autres fixtures
            $reference = 'annonce_' . $i;
            $this->addReference($reference,$annonce);
        }

        $manager->flush();
    }

    /**
     * Liste des classes de fixtures qui doivent être chargés avant celle-ci
     */
    public function getDependencies()
    {
        return [
            CategorieFixtures::class,
            UserFixtures::class
        ];
    }
}

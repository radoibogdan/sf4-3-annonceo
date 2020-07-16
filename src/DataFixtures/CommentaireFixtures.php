<?php

namespace App\DataFixtures;

use App\Entity\Annonce;
use App\Entity\Categorie;
use App\Entity\Commentaire;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CommentaireFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        // Instanciation de faker
        $faker = Factory::create('fr_FR');

        // Créer 10 commentaires
        for ($i = 0; $i < 10; $i++) {
            $commentaire = new Commentaire();
            $commentaire
                ->setCommentaire($faker->realText(50))
                ->setCreation($faker->dateTimeBetween('-6 months'))
            ;
            // Récupération aléatoire d'une annonce par une référence
            $annonceReference = 'annonce_' . $faker->numberBetween(0, 19);
            /** @var Annonce $annonce */
            $annonce = $this->getReference($annonceReference); // getReference -> renvoie une entité Annonce
            $commentaire->setAnnonce($annonce);

            // Récupération aléatoire d'un user par une référence
            $userReference = 'user_' . $faker->numberBetween(0, 9);
            /** @var User $user */
            $user = $this->getReference($userReference); // getReference -> renvoie une entité user
            $commentaire->setAuteur($user);

            $manager->persist($commentaire);
        }

        $manager->flush();
    }
    /**
     * Liste des classes de fixtures qui doivent être chargés avant celle-ci
     */
    public function getDependencies()
    {
        return [
            UserFixtures::class,
            AnnonceFixtures::class
        ];
    }
}

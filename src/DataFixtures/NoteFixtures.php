<?php

namespace App\DataFixtures;

use App\Entity\Note;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class NoteFixtures extends Fixture implements DependentFixtureInterface
{
        public function load(ObjectManager $manager)
    {
        // Instanciation de faker
        $faker = Factory::create('fr_FR');

        // Créer 10 Notes
        for ($i = 0; $i < 10; $i++) {
            $note = new Note();
            $note
                ->setNote($faker->numberBetween(1,10))
                ->setAvis($faker->sentence(10))
                ->setCreation($faker->dateTimeBetween('-6 months'))
            ;
            // Récupération aléatoire d'un user par une référence
            $userReference1 = 'user_' . $faker->numberBetween(0, 9);
            $userReference2 = 'user_' . $faker->numberBetween(0, 9);
            /** @var User $user1 */
            $user1 = $this->getReference($userReference1); // getReference -> renvoie une entité user
            /** @var User $user2 */
            $user2 = $this->getReference($userReference2); // getReference -> renvoie une entité user
            $note->setAuteur($user1);
            $note->setUser($user2);
            $manager->persist($note);
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
        ];
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Faker\Factory;

class UserFixtures extends Fixture
{
    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {

        $this->passwordEncoder = $passwordEncoder;
    }

    public function load(ObjectManager $manager)
    {
        // Instanciation de faker
        $faker = Factory::create('fr_FR');

        // Créer 10 utilisateurs
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $hash = $this->passwordEncoder->encodePassword($user, 'user' . $i);
            $user
                ->setEmail('user' . $i . '@mail.com')
                ->setPassword($hash)
                ->setPseudo($faker->userName)
                ->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setTelephone($faker->phoneNumber)
                ->setInscription($faker->dateTimeBetween('-1 year'))
            ;
            $manager->persist($user);
            // Lier la référence ($reference) à l'entité ($user), pour la récupérer dans d'autres fixtures
            $reference = 'user_' . $i;
            $this->addReference($reference,$user);
        }

        // Créer 5 moderateurs
        for ($i = 0; $i < 5; $i++) {
            $moderateur = new User();
            $hash = $this->passwordEncoder->encodePassword($moderateur, 'moderateur' . $i);
            $moderateur
                ->setEmail('mod' . $i . '@mail.com')
                ->setPassword($hash)
                ->setRoles(['ROLE_MODERATEUR'])
                ->setPseudo($faker->userName)
                ->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setTelephone($faker->phoneNumber)
                ->setInscription($faker->dateTimeBetween('-1 year'))
            ;
            $manager->persist($moderateur);
        }

        // Créer 2 admins
        for ($i = 0; $i < 2; $i++) {
            $admin = new User();
            $hash = $this->passwordEncoder->encodePassword($admin, 'admin' . $i);
            $admin
                ->setEmail('admin' . $i . '@mail.com')
                ->setPassword($hash)
                ->setRoles(['ROLE_ADMIN'])
                ->setPseudo($faker->userName)
                ->setPrenom($faker->firstName)
                ->setNom($faker->lastName)
                ->setTelephone($faker->phoneNumber)
                ->setInscription($faker->dateTimeBetween('-1 year'))
            ;
            $manager->persist($admin);
        }

        $manager->flush();
    }
}

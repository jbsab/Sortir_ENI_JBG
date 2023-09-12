<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++) {
           $user = new User();
           $user->setFirstName('Prenom Utilisateur '.$i);
           $user->setLastName('Nom Utilisateur '.$i);
           $user->setMail('Utilisateur'.$i.'@mail.com');
           $user->setPhoneNumber(mt_rand(10, 12));
           $user->setUsername('Pseudo'.$i);
           $user->setPassword('test');
           $user->setIsActive(0);
           $user->setIsAdmin(0);
           $user->setProfilePicture(null);
           $manager->persist($user);
        }

        $manager->flush();
    }
}

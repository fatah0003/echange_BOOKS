<?php

namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    private UserPasswordHasherInterface $userPasswordHasher;
    public function __construct(UserPasswordHasherInterface $userPasswordHasher){
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager): void
    {
       for ($i = 0; $i < 3; $i++){
        $user = new User();
        $user
            ->setEmail('nom'. $i .'@gmail.com')
            ->setPassword($this->userPasswordHasher->hashPassword(
                $user,
                '0123456'
            ))
            ;
            $this->addReference('user_'.$i, $user);
            $manager->persist($user);
       }

        $manager->flush();
    }
}

<?php

namespace App\DataFixtures;

use App\Entity\InfosUser;
use DateTimeImmutable;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class InfosUserFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 3; $i++) {
            $infouser = new InfosUser();
            $infouser
            ->setUserName('nom' . $i)
            ->setPhoneNumber('0611111' . $i)
            ->setBirthDate(DateTimeImmutable::createFromFormat('d/m/Y', $i . '/12/2000'))
            ->setCity('Lyon' . $i)
            ->setBio('Je lis des livres, quand j\'ai du temps')
            ->setUser($this->getReference('user_' . $i))
            ;
            $manager->persist($infouser);
        }
        $manager->flush();
    }
    public function getDependencies(): array
    {
        return [
            UserFixtures::class,
        ];
    }
}

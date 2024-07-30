<?php

namespace App\DataFixtures;

use App\Entity\Books;
use App\Enum\ExchangeTypeEnum;
use App\Enum\StateEnum;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class BooksFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 20; $i++){
           $book = new Books();
           $book
                ->setTitle('Astronomie pour tous'.$i)
                ->setAuthor('AINSERI Fatah'.$i)
                ->setIsbn('Z125958'.$i)
                ->setEdition($i.'eme Edition ')
                ->setLocation('Lyon'.$i)
                ->setPages(322)
                ->setDescription('Apprend les bases astronomie de A à Z, regarde le ciel et sois AstroNger'.$i)
                ->setBookCategorie($this->getReference('categorie'. rand(0, 9)))
                ->setExchangeType([ExchangeTypeEnum::PERMANENT])
                ->setState(StateEnum::GOOD)
                ->setUser($this->getReference('user_'. rand(0,2)))
                ;
                $manager->persist($book);
        }

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            CategorieFixtures::class,
            UserFixtures::class,
        ];
    }
}

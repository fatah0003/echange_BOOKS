<?php

namespace App\DataFixtures;

use App\Entity\BookCategorie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategorieFixtures extends Fixture
{
    public const CAT = [
        "Physique",
        "SF Fantasy",
        "Policier",
        "Philosophie",
        "Thriller",
        "Livres pour enfant",
        "Cuisine",
        "Histoire",
        "Finances"
    ];
    public function load(ObjectManager $manager): void
    {
       
        for ($i = 0; $i < 10; $i++){
            $categorie = new BookCategorie();
            $categorie
                ->setName(self::CAT[array_rand(self::CAT)])
                
                ;
                $this->addReference('categorie'.$i, $categorie );
                $manager->persist($categorie);
           }
    
            $manager->flush();
    }
}

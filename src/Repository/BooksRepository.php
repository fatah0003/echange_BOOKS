<?php

namespace App\Repository;

use App\Entity\BookCategorie;
use App\Entity\Books;
use App\Enum\ExchangeTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Books>
 */
class BooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Books::class);
    }

    public function search(
        ?string $title,
        ?string $author,
        ?string $location,
        ?ExchangeTypeEnum $exchangeTypes,
        ?BookCategorie $bookCategorie,
    ): array {
        $qb = $this->createQueryBuilder('b');
        if ($title) {
            $qb->andWhere('b.title LIKE :title')
            ->setParameter('title', '%' . $title . '%')
            ;
        }
        if ($author) {
            $qb->andWhere('b.author LIKE :author')
            ->setParameter('author', '%' . $author . '%')
            ;
        }
        if ($location) {
            $qb->andWhere('b.location LIKE :location')
            ->setParameter('location', '%' . $location . '%')
            ;
        }
        if ($exchangeTypes) {
            $qb->andWhere('b.exchangeType LIKE :exchangeType')
            ->setParameter('exchangeType', '%'.$exchangeTypes->value.'%')
            ;
        }
        if ($bookCategorie) {
            $qb->andWhere('b.bookCategorie = :bookCategorie')
            ->setParameter('bookCategorie', $bookCategorie);
        }
        return $qb->getQuery()->getResult();
    }
    // public function getByCategorieName(string $categorie)
    // {
    //     $qb = $this->createQueryBuilder('b')
    //         ->join('b.bookCategorie', 'bookCategorie')
    //         ->where('bookCategorie.name = :bookCategorie')
    //         ->setParameter('bookCategorie', $categorie);
    //     return $qb->getQuery()->getResult();
    // }

    //    /**
    //     * @return Books[] Returns an array of Books objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Books
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}

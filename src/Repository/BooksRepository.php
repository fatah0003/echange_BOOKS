<?php

namespace App\Repository;

use App\Entity\BookCategorie;
use App\Entity\Books;
use App\Enum\ExchangeTypeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Doctrine\Persistence\ManagerRegistry;
use Knp\Component\Pager\PaginatorInterface;

/**
 * @extends ServiceEntityRepository<Books>
 */
class BooksRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, public PaginatorInterface $paginator)
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
    
    // public function pagination(int $page = 1, int $limit = 12): Paginator
    // {
    //     return new Paginator($this
    //         ->createQueryBuilder('b')
    //         ->setFirstResult(($page - 1) * $limit)
    //         ->setMaxResults($limit)
    //         ->getQuery()
    // );
    // }

    public function pagination (int $page = 1, int $limit = 12): \KNP\Component\Pager\Pagination\PaginationInterface 
    {
        return $this->paginator->paginate(
            $this->createQueryBuilder('b'),
            $page,
            $limit
        );
    }
}

<?php

namespace App\Repository;

use App\Entity\Exchange;
use App\Enum\ExchangeEnum;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Exchange>
 */
class ExchangeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exchange::class);
    }

    public function findLatestSentRequests($user)
    {
        return $this->createQueryBuilder('e')
            ->where('e.userRequester = :user')
            ->setParameter('user', $user)
            ->orderBy('e.createdAt', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();
    }

    public function findLatestReceivedRequests($user)
{
    return $this->createQueryBuilder('e')
        ->andWhere('e.userReceiver = :user')
        ->andWhere('e.status = :status')
        ->setParameter('user', $user)
        ->setParameter('status', ExchangeEnum::PENDING->value)
        ->orderBy('e.createdAt', 'DESC')
        ->setMaxResults(5)
        ->getQuery()
        ->getResult();
}

public function findLatestCompletedRequests($user)
{
    return $this->createQueryBuilder('e')
        ->where('e.status = :status')
        ->setParameter('status', ExchangeEnum::VALIDATED)
        ->andWhere('e.userRequester = :user OR e.userReceiver = :user')
        ->setParameter('user', $user)
        ->orderBy('e.acceptedAt', 'DESC') 
        ->setMaxResults(5)
        ->getQuery()
        ->getResult();
}

//    /**
//     * @return Exchange[] Returns an array of Exchange objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Exchange
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

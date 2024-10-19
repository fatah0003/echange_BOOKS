<?php

namespace App\Repository;

use App\Entity\Conversation;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Conversation>
 */
class ConversationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Conversation::class);
    }

    public function findConvBetweenusers(User $recipient, User $sender): ?Conversation
    {
        $qb = $this->createQueryBuilder('c');
        $qb->join('c.participants', 'p1')
            ->join('c.participants', 'p2')
            ->where('p1.id = :recipientId')
            ->andWhere('p2.id = :senderId')
            ->orWhere('p1.id = :senderId')
            ->andWhere('p2.id = :recipientId')
            ->setParameter('recipientId', $recipient->getId())
            ->setParameter('senderId', $sender->getId())
        ;

        return $qb->getQuery()->getOneOrNullResult();
    }
}

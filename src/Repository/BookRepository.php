<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Dto\ListQuery;
use App\Entity\Book;

/**
 * @extends ServiceEntityRepository<Book>
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Book::class);
    }

    public function getList(ListQuery $query): array
    {
        return $this->createQueryBuilder('q')
            ->setMaxResults($query->getMaxResults())
            ->setFirstResult($query->getFirstResult())
           ->getQuery()
           ->getArrayResult();
    }

    public function findByAuthorLastname(string $lastname): array
    {
        return $this->createQueryBuilder('q')
            ->join('q.authors', 'authors', 'WITH', 'authors.lastname=:lastname')
            ->setParameter('lastname', $lastname)
           ->getQuery()
           ->getArrayResult();
    }
}

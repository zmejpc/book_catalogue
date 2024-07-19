<?php

namespace App\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Dto\ListQuery;
use App\Entity\Author;

/**
 * @extends ServiceEntityRepository<Author>
 */
class AuthorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Author::class);
    }

    public function getList(ListQuery $query): array
    {
        return $this->createQueryBuilder('q')
            ->setMaxResults($query->getMaxResults())
            ->setFirstResult($query->getFirstResult())
           ->getQuery()
           ->getArrayResult();
    }
}

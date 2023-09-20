<?php

namespace App\Repository;

use App\Entity\FiltersSorties;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FiltersSorties>
 *
 * @method FiltersSorties|null find($id, $lockMode = null, $lockVersion = null)
 * @method FiltersSorties|null findOneBy(array $criteria, array $orderBy = null)
 * @method FiltersSorties[]    findAll()
 * @method FiltersSorties[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FiltersSortiesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FiltersSorties::class);
    }

    public function save(FiltersSorties $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(FiltersSorties $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }
}

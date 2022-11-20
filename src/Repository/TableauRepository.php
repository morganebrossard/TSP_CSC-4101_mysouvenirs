<?php

namespace App\Repository;

use App\Entity\Tableau;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Tableau>
 *
 * @method Tableau|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tableau|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tableau[]    findAll()
 * @method Tableau[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TableauRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tableau::class);
    }

    public function add(Tableau $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Tableau $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

}

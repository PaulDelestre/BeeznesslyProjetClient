<?php

namespace App\Repository;

use App\Entity\TypeOfUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TypeOfUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method TypeOfUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method TypeOfUser[]    findAll()
 * @method TypeOfUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TypeOfUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TypeOfUser::class);
    }

    // /**
    //  * @return TypeOfUser[] Returns an array of TypeOfUser objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?TypeOfUser
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

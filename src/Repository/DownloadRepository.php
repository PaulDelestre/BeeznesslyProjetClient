<?php

namespace App\Repository;

use App\Entity\Download;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Download|null find($id, $lockMode = null, $lockVersion = null)
 * @method Download|null findOneBy(array $criteria, array $orderBy = null)
 * @method Download[]    findAll()
 * @method Download[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DownloadRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Download::class);
    }

    /**
     * @return Download[] Returns an array of Download objects
     */

    public function findByExpert($expert)
    {
        return $this->createQueryBuilder('d')
            ->join('d.ebook', 'e')
            ->where('e.user = :expert')
            ->setParameter('expert', $expert)
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Download[] Returns an array of Download objects
     */

    public function findByEbook($ebookId)
    {
        return $this->createQueryBuilder('d')
            ->join('d.ebook', 'e')
            ->where('e.id = :ebookId')
            ->setParameter('ebookId', $ebookId)
            ->getQuery()
            ->getResult()
        ;
    }

    /*
    public function findOneBySomeField($value): ?Download
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}

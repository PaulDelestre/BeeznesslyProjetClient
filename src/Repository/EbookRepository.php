<?php

namespace App\Repository;

use App\Entity\Ebook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Data\SearchEbooksData;

/**
 * @method Ebook|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ebook|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ebook[]    findAll()
 * @method Ebook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EbookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ebook::class);
    }

    public function searchEbooks(searchEbooksData $search): array
    {
        $query = $this
            ->createQueryBuilder('ebook')
            ->from($this->_entityName, 'e')
            // A finir pour n'afficher que les
            // ->where('u.roles LIKE :roles')
            // ->andwhere('u.isValidated =:isValidated')
            // ->setParameter('roles', '%"' . 'ROLE_EXPERT' . '"%')
            // ->setParameter('isValidated', true)
            ->leftJoin('ebook.expertise', 'expertise');

        if (!empty($search->expertise)) {
            $query = $query
                ->andWhere('expertise.id IN (:expertise)')
                ->setParameter('expertise', $search->expertise);
        }

        if (!empty($search->service)) {
            $query = $query
                ->andWhere('service.id IN (:service)')
                ->setParameter('service', $search->service);
        }

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('ebook.title LIKE :q 
                OR ebook.description LIKE :q
                OR ebook.editorName LIKE :q 
                OR ebook.author LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        return $query->getQuery()->getResult();
    }
}

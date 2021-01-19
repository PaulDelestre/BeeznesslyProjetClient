<?php

namespace App\Repository;

use App\Entity\Ebook;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Data\SearchEbooksData;
use Knp\Component\Pager\PaginatorInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;

/**
 * @method Ebook|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ebook|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ebook[]    findAll()
 * @method Ebook[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EbookRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Ebook::class);
        $this->paginator = $paginator;
    }

     /**
     * @return PaginationInterface
     */
    public function searchEbooks(searchEbooksData $search): PaginationInterface
    {
        $query = $this
            ->createQueryBuilder('ebook')
            ->from($this->_entityName, 'e')
            ->leftJoin('ebook.expertise', 'expertise');

        if (!empty($search->expertise)) {
            $query = $query
                ->andWhere('expertise.id IN (:expertise)')
                ->setParameter('expertise', $search->expertise);
        }

        if (!empty($search->user)) {
            $query = $query
                ->andWhere('user.id IN (:user)')
                ->setParameter('user', $search->user);
        }

        if (!empty($search->q)) {
            $query = $query
                ->andWhere('ebook.title LIKE :q 
                OR ebook.description LIKE :q
                OR ebook.editorName LIKE :q
                OR ebook.author LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        $query = $query->getQuery()->getResult();
        return $this->paginator->paginate(
            $query,
            1,
            12
        );
    }
}

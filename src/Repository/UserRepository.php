<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Data\SearchExpertsData;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newEncodedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newEncodedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    public function findByExpert()
    {
        $qb = $this->_em->createQueryBuilder();
        $qb->select('u')
        ->from($this->_entityName, 'u')
        ->where('u.roles LIKE :roles')
        ->andwhere('u.isValidated =:isValidated')
        ->setParameter('roles', '%"' . 'ROLE_EXPERT' . '"%')
        ->setParameter('isValidated', true);

        return $qb->getQuery()->getResult();
    }

    public function searchExperts(searchExpertsData $search): array
    {
        $query = $this
            ->createQueryBuilder('user')
            ->from($this->_entityName, 'u')
            // A finir pour n'afficher que les
            // ->where('u.roles LIKE :roles')
            // ->andwhere('u.isValidated =:isValidated')
            // ->setParameter('roles', '%"' . 'ROLE_EXPERT' . '"%')
            // ->setParameter('isValidated', true)
            ->innerJoin('user.provider', 'provider')
            ->leftJoin('user.expertise', 'expertise');
    
        if (!empty($search->provider)) {
            $query = $query
                ->andWhere('provider.id IN (:provider)')
                ->setParameter('provider', $search->provider);
        }

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
                ->andWhere('user.companyName LIKE :q 
                OR user.description LIKE :q 
                OR user.firstname LIKE :q')
                ->setParameter('q', "%{$search->q}%");
        }

        return $query->getQuery()->getResult();
    }
}

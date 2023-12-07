<?php

namespace App\Repository;

use App\Entity\DepenseType;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<DepenseType>
 *
 * @method DepenseType|null find($id, $lockMode = null, $lockVersion = null)
 * @method DepenseType|null findOneBy(array $criteria, array $orderBy = null)
 * @method DepenseType[]    findAll()
 * @method DepenseType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepenseTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DepenseType::class);
    }

    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.user = :val')
            ->setParameter('val', $user)
            ->orderBy('m.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return DepenseType[] Returns an array of DepenseType objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?DepenseType
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

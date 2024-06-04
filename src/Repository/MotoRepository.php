<?php

namespace App\Repository;

use App\Entity\Moto;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Moto>
 *
 * @method Moto|null find($id, $lockMode = null, $lockVersion = null)
 * @method Moto|null findOneBy(array $criteria, array $orderBy = null)
 * @method Moto[]    findAll()
 * @method Moto[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MotoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Moto::class);
    }

//    /**
//     * @return Moto[] Returns an array of Moto objects
//     */
    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.user = :val')
            ->setParameter('val', $user)
            ->orderBy('m.marque', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findDeactivatedByUser(User $user): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.deletedAt is not null')
            ->andWhere('m.user = :val')
            ->setParameter('val', $user)
            ->orderBy('m.marque', 'ASC')
            ->getQuery()
            ->getResult()
            ;
    }

//    public function findOneBySomeField($value): ?Moto
//    {
//        return $this->createQueryBuilder('m')
//            ->andWhere('m.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\Depense;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Depense>
 *
 * @method Depense|null find($id, $lockMode = null, $lockVersion = null)
 * @method Depense|null findOneBy(array $criteria, array $orderBy = null)
 * @method Depense[]    findAll()
 * @method Depense[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DepenseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Depense::class);
    }

    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('d')
            ->innerJoin('d.moto', 'm')
            ->where('m.deletedAt is null')
            ->andWhere('d.user = :val')
            ->setParameter('val', $user)
            ->orderBy('d.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findLastDepense(User $user){
        return $this->createQueryBuilder('m')
            ->andWhere('m.user = :val')
            ->setParameter('val', $user)
            ->orderBy('m.date', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Depense[] Returns an array of Depense objects
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

//    public function findOneBySomeField($value): ?Depense
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

<?php

namespace App\Repository;

use App\Entity\Entretien;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Entretien>
 *
 * @method Entretien|null find($id, $lockMode = null, $lockVersion = null)
 * @method Entretien|null findOneBy(array $criteria, array $orderBy = null)
 * @method Entretien[]    findAll()
 * @method Entretien[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EntretienRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Entretien::class);
    }

    public function findByUser(User $user): array
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.user = :val')
            ->setParameter('val', $user)
            ->orderBy('m.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findLastEntretien(User $user){
        return $this->createQueryBuilder('m')
            ->andWhere('m.user = :val')
            ->setParameter('val', $user)
            ->orderBy('m.date', 'ASC')
            ->setMaxResults(1)
            ->getQuery()
            ->getResult();
    }

//    /**
//     * @return Entretien[] Returns an array of Entretien objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Entretien
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

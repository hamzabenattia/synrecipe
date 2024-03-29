<?php

namespace App\Repository;

use App\Entity\Recipie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Recipie>
 *
 * @method Recipie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Recipie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Recipie[]    findAll()
 * @method Recipie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecipieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Recipie::class);
    }

//    /**
//     * @return Recipie[] Returns an array of Recipie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Recipie
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }





public function getPublicRecipie(?int $nbR) : array
        {

            return $this->createQueryBuilder('r')
            ->where('r.isPublic = true')
            ->setMaxResults($nbR)
            ->orderBy('r.createdAt', 'DESC')
            ->getQuery()
            ->getResult()
            ;
            

        }
        
}

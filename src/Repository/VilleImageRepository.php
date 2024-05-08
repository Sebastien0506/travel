<?php

namespace App\Repository;

use App\Entity\VilleImage;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VilleImage>
 *
 * @method VilleImage|null find($id, $lockMode = null, $lockVersion = null)
 * @method VilleImage|null findOneBy(array $criteria, array $orderBy = null)
 * @method VilleImage[]    findAll()
 * @method VilleImage[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VilleImageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VilleImage::class);
    }

//    /**
//     * @return VilleImage[] Returns an array of VilleImage objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VilleImage
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

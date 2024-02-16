<?php

namespace App\Repository;

use App\Entity\Seasons;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Seasons>
 *
 * @method Seasons|null find($id, $lockMode = null, $lockVersion = null)
 * @method Seasons|null findOneBy(array $criteria, array $orderBy = null)
 * @method Seasons[]    findAll()
 * @method Seasons[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SeasonsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Seasons::class);
        $this->entityManager = $entityManager;
    }

    public function save(Seasons $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }

    public function remove(Seasons $entity)
    {
        $this->entityManager->remove($entity);
        $this->entityManager->flush();
    }

//    /**
//     * @return Seasons[] Returns an array of Seasons objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Seasons
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

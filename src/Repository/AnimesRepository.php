<?php

namespace App\Repository;

use App\Entity\Animes;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;

/**
 * @extends ServiceEntityRepository<Animes>
 *
 * @method Animes|null find($id, $lockMode = null, $lockVersion = null)
 * @method Animes|null findOneBy(array $criteria, array $orderBy = null)
 * @method Animes[]    findAll()
 * @method Animes[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnimesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, Animes::class);
        $this->entityManager = $entityManager;
    }

    public function save(Animes $entity)
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
    }
    
    public function countAll(): int
    {
        return $this->createQueryBuilder('a')
            ->select('count(a.anime_id)')
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return Animes[] Returns an array of Animes objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Animes
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}

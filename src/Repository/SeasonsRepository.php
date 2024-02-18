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

    public function getCurrentSeason() {
        $currentSeasonDates = $this->getCurrentSeasonDates();
        if ($currentSeasonDates === null) {
            // Gérer le cas où la saison actuelle n'est pas définie
            return null;
        }
    
        $season = $this->createQueryBuilder('s')
                       ->where('s.start >= :start')
                       ->andWhere('s.start <= :end')
                       ->setParameter('start', $currentSeasonDates['start'])
                       ->setParameter('end', $currentSeasonDates['end'])
                       ->getQuery()
                       ->getOneOrNullResult();
    
        if ($season) {
            // Compter le nombre d'animes de la saison
            $animeCount = $season->getAnimesList()->count();
    
            return [
                'season' => $season,
                'animeCount' => $season->getAnimesList()->count(),
                'animes' => $season->getAnimesList(),
            ];
        }
    
        return null;
    }

    public function getAnimesByDayForCurrentSeason() {
        $currentSeason = $this->getCurrentSeason();
        if ($currentSeason === null) {
            return null;
        }
    
        $animes = $currentSeason['animes'];
        $animesByDay = [
            'Lundi' => [],
            'Mardi' => [],
            'Mercredi' => [],
            'Jeudi' => [],
            'Vendredi' => [],
            'Samedi' => [],
            'Dimanche' => [],
        ];
    
        foreach ($animes as $anime) {
            $day = $anime->getPublishDay();
            $publishStart = $anime->getPublishStart();
            $currentDate = new \DateTime();
            $weekEpisodeNumber = $publishStart->diff($currentDate)->days / 7 +1;
    
            if (array_key_exists($day, $animesByDay)) {
                $animesByDay[$day][] = [
                    'title' => $anime->getTitle(),
                    'thumbnail' => $anime->getThumbnailLink(),
                    'publishTime' => $anime->getPublishTime()->format('H:i'),
                    'weekEpisodeNumber' => ceil($weekEpisodeNumber) // Arrondi au nombre entier supérieur
                ];
            }
        }
    
        return $animesByDay;
    }
    

    public function getNumberOfPastSeasons() {
        $currentDate = new \DateTime();
        
        // Utiliser la QueryBuilder pour récupérer le nombre de saisons dont la période de 12 semaines est passée
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->select('count(s.id)')
                     ->where('s.start < :currentDate')
                     ->andWhere('s.start < :twelveWeeksBeforeDate')
                     ->setParameter('currentDate', $currentDate)
                     ->setParameter('twelveWeeksBeforeDate', $currentDate->modify('-12 weeks'));
    
        $query = $queryBuilder->getQuery();
        
        return $query->getSingleScalarResult();
    }

    public function getNumberOfUpcomingSeasons() {
        $currentDate = new \DateTime();
        
        // Utiliser la QueryBuilder pour récupérer le nombre de saisons dont la date de début est dans le futur
        $queryBuilder = $this->createQueryBuilder('s');
        $queryBuilder->select('count(s.id)')
                     ->where('s.start > :currentDate')
                     ->setParameter('currentDate', $currentDate);
    
        $query = $queryBuilder->getQuery();
        
        return $query->getSingleScalarResult();
    }
    

    public function getCurrentSeasonDates() {
        $currentDate = new \DateTime();
        $year = $currentDate->format('Y');
    
        // Ajustez l'année pour la saison d'hiver si nécessaire
        $winterYear = ($currentDate->format('m') <= 2) ? $year - 1 : $year;
    
        // Définition des dates de début et de fin des saisons
        $seasons = [
            'printemps' => [
                'start' => new \DateTime("March 20 $year"),
                'end' => new \DateTime("June 20 $year")
            ],
            'été' => [
                'start' => new \DateTime("June 21 $year"),
                'end' => new \DateTime("September 22 $year")
            ],
            'automne' => [
                'start' => new \DateTime("September 23 $year"),
                'end' => new \DateTime("December 20 $year")
            ],
            'hiver' => [
                'start' => new \DateTime("December 21 $winterYear"),
                'end' => (new \DateTime("March 19 $winterYear"))->modify('+1 year')
            ]
        ];
    
        $previousSeason = null;
    
        // Trouver la saison actuelle
        foreach ($seasons as $name => $dates) {
            if ($currentDate >= $dates['start'] && $currentDate < $dates['end']) {
                return [
                    'season' => $name,
                    'start' => $dates['start'],
                    'end' => $dates['end']
                ];
            }
            $previousSeason = [
                'season' => $name,
                'start' => $dates['start'],
                'end' => $dates['end']
            ];
        }
    
        // Retourne la dernière saison vérifiée si aucune correspondance n'est trouvée
        return $previousSeason;
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

<?php

namespace App\Repository;

use App\Entity\Forecast;
use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Forecast>
 */
class ForecastRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Forecast::class);
    }
    /**
     * @return Forecast[]
     */
    public function findForForecast(Location $location) : array
    {
        $qb = $this -> createQueryBuilder('f');

        $qb
            ->where('f.location = :location')
            ->setParameter('location', $location)
            ->andWhere('f.date >= :now')
            ->setParameter('now', date('Y-m-d'))
            ->addOrderBy('f.date','ASC')
            ;
        
        $query = $qb -> getQuery();
        $forecasts = $query -> getResult();

        return $forecasts;
    }
}

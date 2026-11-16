<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/** @extends ServiceEntityRepository<Location> */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    public function findOneByCity(string $city): ?Location
    {
        return $this->findOneByCityAndCountry($city);
    }

    public function findOneByCityAndCountry(string $city, ?string $country = null): ?Location
    {
        $criteria = ['city' => trim($city)];
        if ($country !== null) {
            $criteria['countryCode'] = strtoupper(trim($country));
        }

        return $this->findOneBy($criteria);
    }
}

<?php

namespace App\Repository;

use App\Entity\Location;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Location>
 *
 * @method Location|null find($id, $lockMode = null, $lockVersion = null)
 * @method Location|null findOneBy(array $criteria, array $orderBy = null)
 * @method Location[]    findAll()
 * @method Location[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Location::class);
    }

    /**
     * @return Location|null Returns a Location object based on city and optional country
     */
    public function findOneByCityAndCountry(string $city, ?string $country): ?Location
    {
        $qb = $this->createQueryBuilder('l');
        $qb->where('l.city = :city')
            ->setParameter('city', $city);

        if ($country) {
            $qb->andWhere('l.country = :country')
                ->setParameter('country', $country);
        }

        return $qb->getQuery()->getOneOrNullResult();
    }
}

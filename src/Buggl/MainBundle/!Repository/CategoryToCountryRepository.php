<?php

namespace Buggl\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * CategoryToCountryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CategoryToCountryRepository extends EntityRepository
{
    public function findByCountry($country)
    {
        $qb = $this->createQueryBuilder('c');
        
        $qb->select('c')
           ->where('c.country = :country')
           ->setParameter('country',$country);

        return $qb->getQuery()->getResult();
    }
}

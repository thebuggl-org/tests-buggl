<?php

namespace Buggl\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * EGuideLocationRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EGuideLocationRepository extends EntityRepository
{
	public function suggestLocation($qString)
	{
		$qb = $this->createQueryBuilder('location');

		$qb->select('location.address')
		   ->distinct('location.address')
		   ->where('location.address LIKE :string')
		   ->setParameter('string','%'.$qString.'%');

		return $qb->getQuery()->getResult();
	}
}

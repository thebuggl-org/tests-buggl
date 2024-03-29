<?php

namespace Buggl\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ItineraryRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ItineraryRepository extends EntityRepository
{
	public function findByGuide(\Buggl\MainBundle\Entity\EGuide $eguide)
	{
		$qb = $this->createQueryBuilder('itinerary');
		
		$qb->select('itinerary');
		$qb->where('itinerary.e_guide = :eguide');
		$qb->setParameter('eguide',$eguide);
		$qb->orderBy("itinerary.day_num","ASC");
		
		return $qb->getQuery()->getResult();
	}

	public function getNextSchedule(\Buggl\MainBundle\Entity\EGuide $eguide, $currentDay = 1)
	{
		$qb = $this->createQueryBuilder('itinerary');
		
		$qb->select('itinerary');
		$qb->where('itinerary.e_guide = :eguide AND itinerary.day_num > :day');
		// $qb->setParameter('eguide',$eguide);
		$qb->setParameters(array('eguide' => $eguide, 'day' => $currentDay));
		$qb->orderBy("itinerary.day_num","ASC");
		
		return $qb->getQuery()->getResult();
	}
}

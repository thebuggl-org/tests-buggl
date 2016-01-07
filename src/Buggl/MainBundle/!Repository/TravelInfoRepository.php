<?php

namespace Buggl\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

use Buggl\MainBundle\Entity\LocalAuthor;
use Buggl\MainBundle\Entity\TravelInfo;

/**
 * TravelInfoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class TravelInfoRepository extends EntityRepository
{
	
	public function getByLocalAuthor( LocalAuthor $localAuthor, $returnNewIfNull = false )
	{
		$qb = $this->createQueryBuilder('travelInfo');
		
		$qb->select('travelInfo')
		   ->where('travelInfo.localAuthor = :localAuthor')
		   ->setParameter('localAuthor', $localAuthor);
		
		try{
			$travelInfo = $qb->getQuery()->getSingleResult();
		}catch(\Doctrine\ORM\NoResultException $e) {
			$travelInfo =  $returnNewIfNull ? new TravelInfo() : null;
		}	
		
		return $travelInfo;
	}
}

<?php

namespace Buggl\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

use Buggl\MainBundle\Entity\SocialMedia;

/**
 * SocialMediaRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SocialMediaRepository extends EntityRepository
{
	
	public function findByLocalAuthor($localAuthor, $returnNewIfNull = false)
	{
		$qb = $this->createQueryBuilder('socialmedia');
		
		$qb->select('socialmedia');
		$qb->where('socialmedia.localAuthor = :localAuthor');
		$qb->setParameter('localAuthor', $localAuthor);
		
		try{
			$socialMedia = $qb->getQuery()->getSingleResult();
		}catch(\Doctrine\ORM\NoResultException $e) {
			$socialMedia =  $returnNewIfNull ? new SocialMedia() : null;
		}
		
		return $socialMedia;		
	}
}
<?php

namespace Buggl\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * FollowerRespository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class FollowerRepository extends EntityRepository
{
	public function findOneByLocalAuthor($localAuthor)
	{
		$qb = $this->createQueryBuilder('follower');
		
		$qb->select('follower');
		
		$qb->where("follower.localAuthor = :localAuthor");
		$qb->setParameter('localAuthor',$localAuthor);
		
		try{
			return $qb->getQuery()->getSingleResult();
		}catch(\Doctrine\ORM\NoResultException $e) {}
		
		return null;
	}

}
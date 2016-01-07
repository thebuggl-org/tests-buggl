<?php

namespace Buggl\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * SpotTypeRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class SpotTypeRepository extends EntityRepository
{
	public function findByIds($ids)
	{
		$qb = $this->createQueryBuilder('spotType');
		
        if(!empty($ids)){
            $qb->select('spotType')->where($qb->expr()->in('spotType.id',$ids));
            return $qb->getQuery()->getResult();    
        }
		
		return array();
	}
}

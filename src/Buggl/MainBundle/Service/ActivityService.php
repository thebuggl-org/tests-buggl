<?php

namespace Buggl\MainBundle\Service;

use Doctrine\ORM\Query\ResultSetMapping;

class ActivityService
{
	protected $entityManager;

	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}

	public function getActivityFeed($user,$offset,$limit,$endDate)
	{
		if(strpos(get_class($user),'AdminUsers') !== false){
			return $this->getActivityFeedForAdmin();
		}

		return $this->getActivityFeedForLocal($user,$offset,$limit,$endDate);
	}

	private function getActivityFeedForAdmin()
	{
		// NOTE: To be implemented if necessary
		return array();
	}

	private function getActivityFeedForLocal($user,$offset,$limit,$endDate)
	{
		$follower = $this->entityManager->getRepository('BugglMainBundle:Follower')->findOneByLocalAuthor($user);
		$followedIds = is_null($follower) ? array() : json_decode($follower->getFollowing(),true);
		if(empty($followedIds)){
			$followedIds = array(0);
		}

		$rsm = new ResultSetMapping();
		$rsm->addEntityResult('Buggl\MainBundle\Entity\LocalAuthor', 'user');
		$rsm->addFieldResult('user', 'id', 'id');
		$rsm->addFieldResult('user', 'first_name', 'firstName');
		$rsm->addFieldResult('user', 'last_name', 'lastName');
		$rsm->addFieldResult('user', 'slug', 'slug');
		$rsm->addFieldResult('user', 'is_local_author', 'isLocalAuthor');
		$rsm->addFieldResult('user', 'profile', 'profile');
		$query = $this->entityManager
					  ->createNativeQuery('SELECT `local_author`.`id`,`local_author`.`first_name`,`local_author`.`last_name`,`local_author`.`slug`,`local_author`.`is_local_author` FROM `local_author` LEFT JOIN `profile` ON `local_author`.`id` = `profile`.`local_author_id` WHERE 1 AND `local_author`.`id` IN ('.implode(',',$followedIds).')', $rsm);

		$locals = $query->getResult();

		return $this->entityManager->getRepository('BugglMainBundle:Activity')->findByActors($locals,$user,$user->getDateJoined(),$endDate,$offset,$limit);
	}
}
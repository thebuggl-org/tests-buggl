<?php

namespace Buggl\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * MessageToUserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class MessageToUserRepository extends EntityRepository
{	
	public function getLatestMessage($thread,$user,$status=null)
	{
		$qb = $this->createQueryBuilder('messageToUser');
		
		$qb->select('messageToUser');
		$qb->leftJoin('messageToUser.message', 'message');
		
		$statusClause = '';
		if(!is_null($status))
			$statusClause = ' AND messageToUser.status = :status';
			 
		$qb->where('messageToUser.thread = :thread AND messageToUser.user = :user'.$statusClause);
		$qb->setParameter('thread',$thread);
		$qb->setParameter('user',$user);
		
		if(!is_null($status)){
			$qb->setParameter('status',$status);
		}
		
		$qb->orderBy("message.dateCreated","DESC");
		$qb->setFirstResult(0);
		$qb->setMaxResults(1);
		
		try{
			return $qb->getQuery()->getSingleResult();
		}catch(\Doctrine\ORM\NoResultException $e) {}
		
		return null;
	}
	
	public function countMessagesByStatus($user, $status)
	{
		$qb = $this->createQueryBuilder('messageToUser');
		
		$qb->select('COUNT(messageToUser)');
		
		$params = array();
		$whereClause = "messageToUser.user = :user AND messageToUser.messagetype != 'eguiderequest'";
		$params['user'] = $user;
		
		$statusConditions = array();
		foreach($status as $key => $val){
			$statusConditions[] = 'messageToUser.status = '.':status_'.$key;
			 $params['status_'.$key] = $val;
		}	//print_r($val); die;
		$whereClause .= ' AND ('.implode(' OR ',$statusConditions).')';
		
		$qb->where($whereClause);
		
		foreach($params as $key => $val){
			$qb->setParameter($key,$val);
		}
		//echo $qb;
		//print_r($qb->getQuery()->getSingleScalarResult());
		return $qb->getQuery()->getSingleScalarResult();
	}
	
	public function getMessagesOfThread($messageThreadToUser, $offset=0, $limit=0)
	{
		$qb = $this->createQueryBuilder('messageToUser');
		
		$qb->select('messageToUser');
		$qb->leftJoin('messageToUser.message', 'message');
		
		$qb->where("messageToUser.thread = :thread AND messageToUser.user = :user");
		$qb->setParameter('thread',$messageThreadToUser->getThread());
		$qb->setParameter('user',$messageThreadToUser->getUser());
		
		$qb->orderBy("message.dateCreated","ASC");
		
		if($limit>0){
			$qb->setFirstResult($offset);
			$qb->setMaxResults($limit);
		}
		
		return $qb->getQuery()->getResult();		
	}

	public function findAllFilterByExcludingStatus( $excludedStatus, $limit=0, $page=1 )
	{
		$qb = $this->createQueryBuilder('messageToUser');

		$qb->select('messageToUser')
		   ->where('messageToUser.status != :status')
		   ->setParameter('status',$excludedStatus);

		if($limit > 0){ 
			$offset = ($page-1) * $limit;

            $qb->setFirstResult($offset)
               ->setMaxResults($limit);
        }

        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;
	}

	public function countRequestByStatus($user, $status)
	{
		$qb = $this->createQueryBuilder('messageToUser');
		
		$qb->select('COUNT(messageToUser)');
		
		$params = array();
		$whereClause = "messageToUser.user = :user AND messageToUser.messagetype = 'eguiderequest'";
		$params['user'] = $user;
		
		$statusConditions = array();
		foreach($status as $key => $val){
			$statusConditions[] = 'messageToUser.status = '.':status_'.$key;
			 $params['status_'.$key] = $val;
		}	//print_r($val); die;
		$whereClause .= ' AND ('.implode(' OR ',$statusConditions).')';
		
		$qb->where($whereClause);
		
		foreach($params as $key => $val){
			$qb->setParameter($key,$val);
		}
		//echo $qb;
		//print_r($qb->getQuery()->getSingleScalarResult());
		return $qb->getQuery()->getSingleScalarResult();
	}
}
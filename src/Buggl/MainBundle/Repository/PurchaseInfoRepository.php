<?php

namespace Buggl\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * PurchaseInfoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PurchaseInfoRepository extends EntityRepository
{
	public function countDownloadsForSeller($seller)
	{
		$qb = $this->createQueryBuilder('purchaseInfo');

		$qb->select('COUNT(purchaseInfo)');
		$qb->where('purchaseInfo.seller = :seller');
		$qb->setParameter('seller',$seller);

		return $qb->getQuery()->getSingleScalarResult();
	}

	public function sumNetAmountForSeller($seller)
	{
		$qb = $this->createQueryBuilder('purchaseInfo');

		$qb->select('SUM(purchaseInfo.netAmount)');
		$qb->where('purchaseInfo.seller = :seller');
		$qb->setParameter('seller',$seller);

		return $qb->getQuery()->getSingleScalarResult();
	}

	public function sumNetAmountForBuggl()
	{
		$qb = $this->createQueryBuilder('purchaseInfo');

		$qb->select('SUM(purchaseInfo.bugglFee)');

		return $qb->getQuery()->getSingleScalarResult();
	}

	/**
	 * Transaction of user be it selling or purchasing
	 * @param  LocalAuthor  $user   []
	 * @param  integer $offset 		[]
	 * @param  integer $limit  		[]
	 *
	 * @author Vincent Farly G. Taboada <farly.taboada>
	 */
	public function findByUser($user,$offset=0,$limit=0)
	{
		$qb = $this->createQueryBuilder('purchaseInfo');

		$qb->select('purchaseInfo');
		$qb->where('purchaseInfo.buyer = :user OR purchaseInfo.seller = :user');
		$qb->setParameter('user',$user);
		$qb->addOrderBy('purchaseInfo.dateOfTransaction','DESC');

		if($limit > 0){
			$qb->setFirstResult($offset);
			$qb->setMaxResults($limit);
		}

		$paginator = new Paginator($qb->getQuery(), true);

		return $paginator;
	}

	public function findByBuyer($user, $offset=0, $limit=0)
	{
		$qb = $this->createQueryBuilder('purchaseInfo');

		$qb->select('purchaseInfo');
		$qb->where('purchaseInfo.buyer = :buyer');
		$qb->setParameter('buyer',$user);
		$qb->addOrderBy('purchaseInfo.dateOfTransaction','DESC');

		if($limit > 0){
			$qb->setFirstResult($offset);
			$qb->setMaxResults($limit);
		}

		$paginator = new Paginator($qb->getQuery(), true);

		return $paginator;
	}

	public function findBySeller($user, $offset=0, $limit=0)
	{
		$qb = $this->createQueryBuilder('purchaseInfo');

		$qb->select('purchaseInfo');
		$qb->where('purchaseInfo.seller = :seller');
		$qb->setParameter('seller',$user);
		$qb->addOrderBy('purchaseInfo.dateOfTransaction','DESC');

		if($limit > 0){
			$qb->setFirstResult($offset);
			$qb->setMaxResults($limit);
		}

		$paginator = new Paginator($qb->getQuery(), true);

		return $paginator;
	}

	public function findOneByBuyerAndEguide($buyer, $eguide)
	{
		$qb = $this->createQueryBuilder('purchaseInfo');

		$qb->select('purchaseInfo');
		$qb->where('purchaseInfo.buyer = :buyer AND purchaseInfo.eguide = :eguide');
		$qb->setParameter('buyer',$buyer);
		$qb->setParameter('eguide',$eguide);

		try{
			return $qb->getQuery()->getSingleResult();
		}
		catch(\Doctrine\ORM\NoResultException $e) {}

		return null;
	}

	public function findEguideWithHighestNetAmount($limit=10,$page=1)
	{
		$qb = $this->createQueryBuilder('purchaseInfo');

		$qb->select(array("sum(purchaseInfo.amount)","purchaseInfo","sum(purchaseInfo.amount) AS total"))
		   ->addGroupBy('purchaseInfo.eguide')
		   ->orderBy('total','DESC');

		if($limit > 0){
			$offset = ($page-1)*$limit;
			$qb->setFirstResult($offset);
			$qb->setMaxResults($limit);
		}

		// $paginator = new Paginator($qb->getQuery(), true);

		// return $paginator;
		return $qb->getQuery()->getResult();
	}

	public function findEguideWithHighestNetAmountFilteredByCountry($limit=10,$page=1)
	{
		$qb = $this->createQueryBuilder('purchaseInfo');

		$qb->select(array("sum(purchaseInfo.amount)","purchaseInfo",'sum(purchaseInfo.amount) AS total'))
		   ->leftJoin("purchaseInfo.eguide","eguide")
		   ->addGroupBy('eguide.country')
		   ->orderBy('total','DESC');

		if($limit > 0){
			$offset = ($page-1)*$limit;
			$qb->setFirstResult($offset);
			$qb->setMaxResults($limit);
		}

		return $qb->getQuery()->getResult();
	}

	public function findUserRank( $country, $limit = 10, $page = 1 )
	{
		$qb = $this->createQueryBuilder('purchaseInfo');

		$qb->select(
				array(
					'count' => 'count( purchaseInfo.id )',
					'object' => 'purchaseInfo',
					'total' => 'count( purchaseInfo.id ) AS total'
				)
			);

		if( $country ){
			$qb->leftJoin('purchaseInfo.buyer','localAuthor')
			   ->innerJoin('BugglMainBundle:Location','location','WITH','location.localAuthor = localAuthor')
			   ->leftJoin('location.city','city')
			   ->leftJoin('city.country','country')
			   ->where('country.id = :country')
			   ->setParameter('country',$country);
		}


		$qb->addGroupBy('purchaseInfo.buyer')
		   ->orderBy('total','DESC');

		if($limit > 0){
			$offset = ($page-1)*$limit;
			$qb->setFirstResult($offset);
			$qb->setMaxResults($limit);
		}

		return $qb->getQuery()->getResult();
	}

	public function findBySearchFilterGuide($key,$limit=0,$page=1)
	{
		$qb = $this->createQueryBuilder("purchaseInfo");

		$qb->select('purchaseInfo')
		   ->leftJoin('purchaseInfo.eguide','eguide')
		   ->where('eguide.plainTitle LIKE :name')
		   ->setParameter('name','%'.$key.'%')
		   ->orderBy('purchaseInfo.dateOfTransaction','DESC');

		if($limit > 0){
			$offset = ($page-1)*$limit;
			$qb->setFirstResult($offset);
			$qb->setMaxResults($limit);
		}

		$paginator = new Paginator($qb->getQuery(), true);

		return $paginator;
	}

	public function findBySearchFilterSeller($key,$limit=0,$page=1)
	{
		$qb = $this->createQueryBuilder("purchaseInfo");

		$qb->select('purchaseInfo')
		   ->leftJoin('purchaseInfo.seller','localAuthor')
		   ->where('localAuthor.firstName LIKE :name OR localAuthor.lastName LIKE :name')
		   ->setParameter('name','%'.$key.'%')
		   ->orderBy('purchaseInfo.dateOfTransaction','DESC');

		if($limit > 0){
			$offset = ($page-1)*$limit;
			$qb->setFirstResult($offset);
			$qb->setMaxResults($limit);
		}

		$paginator = new Paginator($qb->getQuery(), true);

		return $paginator;
	}

	public function findBySearchFilterBuyer($key,$limit=0,$page=1)
	{
		$qb = $this->createQueryBuilder("purchaseInfo");

		$qb->select('purchaseInfo')
		   ->leftJoin('purchaseInfo.buyer','localAuthor')
		   ->where('localAuthor.firstName LIKE :name OR localAuthor.lastName LIKE :name')
		   ->setParameter('name','%'.$key.'%')
		   ->orderBy('purchaseInfo.dateOfTransaction','DESC');

		if($limit > 0){
			$offset = ($page-1)*$limit;
			$qb->setFirstResult($offset);
			$qb->setMaxResults($limit);
		}

		$paginator = new Paginator($qb->getQuery(), true);

		return $paginator;
	}

	public function findRecent($limit=0,$page=1)
	{
		$qb = $this->createQueryBuilder("purchaseInfo");

		$qb->select('purchaseInfo')
		   ->orderBy('purchaseInfo.dateOfTransaction','DESC');

		if($limit > 0){
			$offset = ($page-1)*$limit;
			$qb->setFirstResult($offset);
			$qb->setMaxResults($limit);
		}

		$paginator = new Paginator($qb->getQuery(), true);
		return $paginator;
	}
}
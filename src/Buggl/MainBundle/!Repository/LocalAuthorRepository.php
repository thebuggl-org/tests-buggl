<?php

namespace Buggl\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * LocalAuthorRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LocalAuthorRepository extends EntityRepository
{
    // public function findAllReviewByLocalAuthor($localAuthor)
    // {
    //     $qb = $this->createQueryBuilder('localauthorreview');

    //     $qb->select('localauthorreview')
    //        ->where('localauthorreview.localAuthor = :localauthor AND localauthorreview.status = :status')
    //        ->setParameter('localauthor',$localAuthor)
    //        ->setParameter('status',$status);

    //     return $qb->getQuery()->getResult();
    // }

    public function findAll($limit = 0, $offset = 0)
    {
        $qb = $this->createQueryBuilder('localauthor');

        $qb->select('localauthor');
		
        $qb->where(' localauthor.isLocalAuthor = :authorType ')
		   ->setParameter('authorType',1);
		
        if($limit > 0){ 
            $qb->setFirstResult($offset)
               ->setMaxResults($limit);
        }
        
        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;
    }

    public function findAllLocalAuthor($status,$limit = 0,$offset = 0)
    {
        $qb = $this->createQueryBuilder('localauthor');

        $qb->select('localauthor')
           ->where('localauthor.status = :status AND localauthor.isLocalAuthor = :authorType')
           ->setParameter('status',$status)
		   ->setParameter('authorType',1);

        if($limit > 0){ 
            $qb->setFirstResult($offset)
               ->setMaxResults($limit);
        }
        
        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;
    }

    public function findAllWithFilters( $limit = 0, $offset = 0, $name = '' )
    {
        $qb = $this->createQueryBuilder('localauthor');

        $qb->select('localauthor');

        if( strlen($name) ){
            $qb->where(' (localauthor.firstName LIKE :name OR localauthor.lastName LIKE :name) AND localauthor.isLocalAuthor = :authorType ')
               ->setParameter('name','%'.$name.'%')
			   ->setParameter('authorType',1);
        }
		else{
            $qb->where(' localauthor.isLocalAuthor = :authorType ')
			   ->setParameter('authorType',1);
		}

        if($limit > 0){ 
            $qb->setFirstResult($offset)
               ->setMaxResults($limit);
        }
        
        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;   
    }


}

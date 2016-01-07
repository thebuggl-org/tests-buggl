<?php

namespace Buggl\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * KeywordRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class KeywordRepository extends EntityRepository
{
	public function findByKeywords($keywords, $status, $limit=0, $page=1)
	{
		$qb = $this->createQueryBuilder("keywords");

        $qb->select(array("keywords,COUNT(eguide.id) as guideCount"))
           ->leftJoin("keywords.eGuide","eguide")
           ->where("eguide.status = :status")
           ->andWhere($this->generateOrWhereClause($keywords))
           ->groupBy("eguide.id")
           ->orderBy("guideCount", 'DESC')
           ->setParameter('status',$status);


        if($limit > 0){
        	$offset = ($page - 1) * $limit;
           	$qb->setMaxResults($limit)
              ->setFirstResult($offset);
        }

        $paginator = new Paginator($qb->getQuery(),false);
        return $paginator;
	}


	private function generateOrWhereClause($keywords)
	{
		$clause = array();

		foreach($keywords as $keyword) {
			$clause[] = "keywords.keyword LIKE '%{$keyword}%'";
		}

		return implode(' OR ', $clause);
	}

}
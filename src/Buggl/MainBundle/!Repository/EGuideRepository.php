<?php

namespace Buggl\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

use Doctrine\ORM\Query\ResultSetMappingBuilder;
/**
 * EGuideRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class EGuideRepository extends EntityRepository
{
    public function findAllFeatured($status)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->where("eguide.isFeaturedInHome = :isFeatured AND eguide.status = :status")
           ->setParameter('isFeatured',1)
           ->setParameter('status',$status);

        return $qb->getQuery()->getResult();
    }

    public function findFeaturedGuidesInHome($isFeatured, $status,$limit = 0,$offset = 0)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->where("eguide.isFeaturedInHome = :isFeatured AND eguide.status = :status")
           ->setParameter('isFeatured',$isFeatured)
           ->setParameter('status',$status);

        if($limit > 0){
           $qb->setMaxResults($limit)
              ->setFirstResult($offset);
        }

        $paginator = new Paginator($qb->getQuery(),true);
        return $paginator;
        // return $qb->getQuery()->getResult();
    }


    public function findTravelGuideByTitle($title,$status,$isFeatured,$limit = 0, $offset = 0)
    {
        $qb = $this->createQueryBuilder('eguide');

        $qb->select('eguide')
           ->where('eguide.isFeaturedInHome != :isFeatured AND eguide.plainTitle LIKE :title AND eguide.status = :published')
           ->setParameter('isFeatured',$isFeatured )
           ->setParameter('title','%'.$title.'%')
           ->setParameter('published',$status);

        if($limit > 0){
            $qb->setMaxResults($limit)
               ->setFirstResult($offset);
        }

        $paginator = new Paginator($qb->getQuery(),true);

        return $paginator;
    }

    public function findByDLCount($limit=0)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->orderBy("eguide.dlCount","DESC");

        if($limit){
            $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function findBySearchCriteria($params = array(), $status, $page = 1, $limit = 0)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide");
        $qb->where("eguide.status = :status")
           ->setParameter("status",$status);
           // ->setParameter("text",'%'.$params["activity"]->getName().'%');

        if(isset($params['activity'])){
            // $qb->leftJoin("eguide.category","category")
            //    ->andWhere("category.id = :activity")
            //    ->setParameter("activity",$params['activity']->getId());
            $qb->andWhere("eguide.categoryNames LIKE :text")
               ->setParameter("text","%".$params['activity_object']->getName().'%');
        }

        if(isset($params['country'])){
            $qb->leftJoin("eguide.country","country");
            $qb->andWhere("country.id = :country");
            $qb->setParameter("country",$params["country"]->getId());
        }
        //inner join
        if(isset($params['duration'])){
            $qb->leftJoin("eguide.duration","duration")
               ->andWhere("duration.id = :duration")
               ->setParameter('duration',$params['duration']->getId());
        }

        if(isset($params['theme'])){
            $qb->leftJoin("eguide.tripTheme","theme")
               ->andWhere("theme.id = :theme")
               ->setParameter('theme',$params['theme']->getId());
        }

        if(isset($params['budget'])){
            if($params['budget'] > 0){
              $qb->andWhere("eguide.budget <= :budget")
                 ->setParameter('budget',$params['budget']);
            }
        }

        $qb->addOrderBy("eguide.dlCount","DESC")
           ->addOrderBy("eguide.dateCreated","DESC");


        if($limit){
            $qb->setMaxResults($limit)
               ->setFirstResult(($page-1)*$limit);
        }

        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;
    }

    public function findByCategoryName($name)
    {
      $qb = $this->createQueryBuilder("eguide");

      $qb->select("eguide");
      $qb->where("eguide.categoryNames LIKE :text")
         ->setParameter('text','%'.$name.'%');

      return $qb->getQuery()->getResult();
    }

    public function findByLocalAuthor(\Buggl\MainBundle\Entity\LocalAuthor $localAuthor)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->where("eguide.localAuthor = :localAuthor")
           ->setParameter("localAuthor",$localAuthor);

        return $qb->getQuery()->getResult();
    }
    public function countByLocalAuthor(\Buggl\MainBundle\Entity\LocalAuthor $localAuthor)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("count(eguide.id)")
           ->where("eguide.localAuthor = :localAuthor")
           ->setParameter("localAuthor",$localAuthor);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findApprovedByLocalAuthor(\Buggl\MainBundle\Entity\LocalAuthor $localAuthor)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->where("eguide.localAuthor = :localAuthor AND eguide.status = 2")
           ->setParameter("localAuthor",$localAuthor);

        return $qb->getQuery()->getResult();
    }

    public function countApprovedByLocalAuthor(\Buggl\MainBundle\Entity\LocalAuthor $localAuthor)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("count(eguide.id)")
           ->where("eguide.localAuthor = :localAuthor AND eguide.status = 2")
           ->setParameter("localAuthor",$localAuthor);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function countBySearchCriteria($params = array(), $status)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("count(eguide.id)");

        $qb->where("eguide.status = :status AND eguide.categoryNames LIKE :text")
           ->setParameter("status",$status)
           ->setParameter(":text",'%'.$params["activity"]->getName().'%');

        if(isset($params['country'])){
            $qb->leftJoin("eguide.country","country");
            $qb->andWhere("country.id = :country");
            $qb->setParameter("country",$params["country"]);
        }

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findFeaturedInCountry($id,$featuredInCountry,$guideStatus=0)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->leftJoin("eguide.country","country")
           ->where("country.id = :country AND eguide.isFeaturedInCountry = :status")
           ->setParameter("country",$id)
           ->setParameter('status',$featuredInCountry);

        if ($guideStatus) {
          $qb->andWhere("eguide.status = :guideStatus");
          $qb->setParameter("guideStatus",$guideStatus);
        }

        return $qb->getQuery()->getResult();
    }

    public function findDLSumByLocalAuthor($localAuthor)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("sum(eguide.dlCount)")
           ->leftJoin("eguide.localAuthor",'localAuthor')
           ->where("localAuthor = :localAuthor")
           ->setParameter('localAuthor',$localAuthor);

        $value = $qb->getQuery()->getSingleScalarResult();

        if(is_null($value)){
          $value = 0;
        }

        return $value;
    }

    public function findByCountryAndCategory( $country, $category, $status, $limit = 0, $page = 1 )
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->where("eguide.status = :status")
           ->setParameter('status',$status);

        if( !is_null($country) ){
            $qb->leftJoin('eguide.country','country')
               ->andWhere('country = :country')
               ->setParameter('country',$country);
        }

        if( !is_null($category) ){
            $qb->leftJoin('eguide.category','category')
               ->andWhere('category = :category')
               ->setParameter('category',$category);
        }

        if($limit > 0){
            $offset = ($page - 1) * $limit;

            $qb->setFirstResult($offset)
               ->setMaxResults($limit);
        }

        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;
    }

    public function findByCountryAndCategoryWithTitle( $country, $category, $title, $status, $limit = 0, $page = 1 )
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->where("eguide.status = :status")
           ->setParameter('status',$status);

        if(!is_null($title) && (0 < strlen(trim($title)) ) ){
          $qb->andWhere('eguide.plainTitle LIKE :title')
            ->setParameter('title', '%'.$title.'%');
        }

        if( !is_null($country) ){
            $qb->leftJoin('eguide.country','country')
               ->andWhere('country = :country')
               ->setParameter('country',$country);
        }

        if( !is_null($category) ){
            $qb->leftJoin('eguide.category','category')
               ->andWhere('category = :category')
               ->setParameter('category',$category);
        }



        if($limit > 0){
            $offset = ($page - 1) * $limit;

            $qb->setFirstResult($offset)
               ->setMaxResults($limit);
        }
        
        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;
    }

    public function findByLocalAuthorPaginator(\Buggl\MainBundle\Entity\LocalAuthor $localAuthor, $status, $limit = 0, $page = 1 )
    {
        $qb = $this->createQueryBuilder("eguide");

        $statusQry = ($status == 3) ? "eguide.status = 2 AND eguide.status = :status" : "eguide.status = :status";

        $qb->select("eguide")
           ->where("$statusQry AND eguide.localAuthor = :localAuthor")
           ->setParameter('status',$status)
           ->setParameter("localAuthor",$localAuthor)
           ->orderBy("eguide.dateUpdated","DESC");

        if($limit > 0){
            $offset = ($page - 1) * $limit;

            $qb->setFirstResult($offset)
               ->setMaxResults($limit);
        }
        //echo $qb; die;
        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;
    }

    public function countByLocalAuthorAndStatus(\Buggl\MainBundle\Entity\LocalAuthor $localAuthor, $status = 0)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("count(eguide.id)");

        $qb->where("eguide.status = :status AND eguide.localAuthor = :localAuthor")
         ->setParameter('status',$status)
         ->setParameter("localAuthor",$localAuthor);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByCountryFilteredByKey($country,$key,$limit=0,$page=1)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->leftJoin("eguide.country","country")
           ->where(
                "country.id = :country AND eguide.plainTitle LIKE :key AND eguide.isFeaturedInCountry = 0 AND eguide.status >= 2 AND eguide.status <= 3"
            )
           ->orderBy("eguide.plainTitle","DESC")
           ->setParameter('country',$country)
           ->setParameter('key','%'.$key.'%');

        if($limit > 0){
            $offset = ($page - 1) * $limit;

            $qb->setFirstResult($offset)
               ->setMaxResults($limit);
        }

        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;
    }

    public function findFeaturedInProfile($localAuthor, $status = 1, $guideStatus = 2)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide");

        $qb->where("eguide.isFeaturedInProfile = :status AND eguide.localAuthor = :localAuthor")
         ->setParameter('status',$status)
         ->setParameter("localAuthor",$localAuthor);

      if ($guideStatus) {
        $qb->andWhere("eguide.status = :guideStatus");
        $qb->setParameter("guideStatus",$guideStatus);
      }

        return $qb->getQuery()->getResult();
    }

    public function countFeaturedInProfile($localAuthor, $status = 1)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("count(eguide)");

        $qb->where("eguide.isFeaturedInProfile = :status AND eguide.localAuthor = :localAuthor")
         ->setParameter('status',$status)
         ->setParameter("localAuthor",$localAuthor);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findBysimilarGuide($id,$tripTheme,$status,$offset=0,$limit=0)
    {
        $qb = $this->createQueryBuilder("similarTravelGuide");

        $qb->select("similarTravelGuide")
           ->where("similarTravelGuide.tripTheme =:tripTheme AND similarTravelGuide.id !=:id AND similarTravelGuide.status =:status")
           ->orderBy("similarTravelGuide.price" , "DESC")
           ->setParameter('tripTheme', $tripTheme)
           ->setParameter('id', $id)
           ->setParameter('status', $status);
        if($limit > 0)
        {
           $qb->setFirstResult($offset);
           $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function findRecentGuides($limit = 5,$status = 2)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->where("eguide.status = :status")
           // ->orderBy("eguide.dateCreated","DESC")
		   ->orderBy("eguide.dateUpdated","DESC")
           ->setParameter('status',$status);

        if($limit > 0){
           $qb->setFirstResult(0);
           $qb->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function freeSearchGuide($ids, $limit, $page=1)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->where($qb->expr()->in('eguide.id', $ids))
           ->orderBy("eguide.dlCount","DESC");

        if($page > 0){
            $offset = ($page - 1) * $limit;

            $qb->setFirstResult($offset);
        }

        $qb->addOrderBy("eguide.dlCount","DESC")
           ->addOrderBy("eguide.dateCreated","DESC");

        $qb->setMaxResults($limit);

        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;
    }

    public function findByKeywords($activity, $location, $status, $limit, $page, $order='relevant')
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->where("eguide.status = :status")
           ->setParameter('status',$status);

        if (strlen($location)) {
            $qb->andWhere("eguide.freeSearch LIKE :location")
               ->setParameter("location","%".$location."%");
        }

        if (count($activity)) {
            $orClause = array();
            foreach ($activity as $key => $each) {
                $bindParameter = $key+1;
                $orClause[] = "eguide.freeSearch LIKE ?{$bindParameter}";
            }
            $qb->andWhere(implode(' OR ', $orClause));

            foreach($activity as $key => $each) {
                $bindParameter = $key+1;
                $qb->setParameter($bindParameter,'%activity:'.trim($each).'%');
            }
        }

        if($page > 0){
            $offset = ($page - 1) * $limit;
            $qb->setFirstResult($offset);
            $qb->setMaxResults($limit);
        }

        $qb = $this->getOrder($qb,$order);

        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;
        // if () {

        // }
    }

    private function getOrder($qb,$order)
    {
        if ($order == 'download') {
            $qb->orderBy('eguide.dlCount','DESC');
        } else if ($order == 'recent'){
            $qb->orderBy('eguide.dateUpdated','DESC');
        }
        return $qb;
    }

    public function findRecentGuidesV2($limit,$status)
    {

        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->where("eguide.status = :status")
           ->setParameter('status',$status)
           ->setFirstResult(0)
           ->setMaxResults($limit)
           ->orderBy("eguide.dateUpdated","DESC");

        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;
    }

    // method for the EGuidePdfUpdateCommand script
    public function findPublishedGuidesWithoutPdf()
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("eguide")
           ->where("eguide.status = :status AND eguide.pdfFilename IS NULL")
           ->setParameter('status',2);

        return $qb->getQuery()->getResult();
    }

    public function findByQuery($sqlStatement)
    {
        $em = $this->getEntityManager();
        $rsm = new ResultSetMappingBuilder($em);
        $rsm->addRootEntityFromClassMetadata('BugglMainBundle:EGuide', 'guide');
        $query = $em->createNativeQuery($sqlStatement, $rsm);
        $eguides = $query->getResult();
        return $eguides;
    }

    public function countFreeGuidesByAuthor(\Buggl\MainBundle\Entity\LocalAuthor $localAuthor)
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("count(eguide.id)");

        $qb->where("eguide.status = :status AND eguide.localAuthor = :localAuthor AND eguide.price = '0.00'")
         ->setParameter('status',2)
         ->setParameter("localAuthor",$localAuthor);

        return $qb->getQuery()->getSingleScalarResult();
    }

    /** 
     *
     * @author Nash Lesigon <nashlesigon@gmail.com>
     * @version - 1.0
     * @param - the parameters accepted by the method
     *  
     */
    public function countPublishedGuides()
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("count(eguide.id)");
        $qb->where("eguide.status = :status")
         ->setParameter('status',2);

        return $qb->getQuery()->getSingleScalarResult();
    }
    
    /** 
    *
    * @author Nash Lesigon <nashlesigon@gmail.com>
    * @version - 1.0
    * @param - the parameters accepted by the method
    *
    */
    public function sumDownloadCount()
    {
        $qb = $this->createQueryBuilder("eguide");

        $qb->select("SUM(eguide.dlCount)");
        $qb->where("eguide.status = :status")
            ->setParameter('status',2);

        return $qb->getQuery()->getSingleScalarResult();
    }
                      
}
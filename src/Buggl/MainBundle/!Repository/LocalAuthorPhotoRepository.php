<?php

namespace Buggl\MainBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
/**
 * LocalAuthorPhotoRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class LocalAuthorPhotoRepository extends EntityRepository
{
    public function findByLocalAuthor( $localAuthor, $limit=0, $page=0 )
    {
        $qb = $this->createQueryBuilder("photo");

        $qb->select("photo")
           ->where("photo.localAuthor = :localAuthor")
           ->setParameter("localAuthor",$localAuthor)
           ->orderBy('photo.id','DESC');

        if($limit > 0){

            $offset = ($page - 1) * $limit;

            $qb->setFirstResult($offset)
               ->setMaxResults($limit);
        }

        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;
    }

    public function findAll( $limit = 0, $page = 0 ){

        $qb = $this->createQueryBuilder("photo");

        $qb->select("photo");


        if($limit > 0){
            $offset = ($page - 1) * $limit;

            $qb->setFirstResult($offset)
               ->setMaxResults($limit);
        }

        $paginator = new Paginator($qb->getQuery(), true);
        return $paginator;
    }

    public function findAllByCity( $city, $limit = 0, $page = 1 )
    {
        $qb = $this->createQueryBuilder("photo");

        $qb->select("photo")
           ->innerJoin('BugglMainBundle:Location','location','WITH','photo.localAuthor = location.localAuthor')
           ->leftJoin('location.city','city')
           ->where('city.id = :city')
           ->setParameter('city',$city);

        if($limit > 0){
            $offset = ($page - 1) * $limit;

            $qb->setFirstResult($offset)
               ->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function countAllByCity($city)
    {
        $qb = $this->createQueryBuilder("photo");

        $qb->select("count(photo.id)")
           ->innerJoin('BugglMainBundle:Location','location','WITH','photo.localAuthor = location.localAuthor')
           ->leftJoin('location.city','city')
           ->where('city.id = :city')
           ->setParameter('city',$city);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findAllByCountry( $country, $limit = 0, $page = 1 )
    {
        $qb = $this->createQueryBuilder("photo");

        $qb->select("photo")
           ->innerJoin('BugglMainBundle:Location','location','WITH','photo.localAuthor = location.localAuthor')
           ->leftJoin('location.city','city')
           ->leftJoin('city.country','country')
           ->where('country.id = :country')
           ->setParameter('country',$country);

        if($limit > 0){
            $offset = ($page - 1) * $limit;

            $qb->setFirstResult($offset)
               ->setMaxResults($limit);
        }

        return $qb->getQuery()->getResult();
    }

    public function countAllByCountry( $country )
    {
        $qb = $this->createQueryBuilder("photo");

        $qb->select("count(photo.id)")
           ->innerJoin('BugglMainBundle:Location','location','WITH','photo.localAuthor = location.localAuthor')
           ->leftJoin('location.city','city')
           ->leftJoin('city.country','country')
           ->where('country.id = :country')
           ->setParameter('country',$country);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function findByPKs($ids)
    {
        $qb = $this->createQueryBuilder('photo');

        $qb->select('photo')
           ->where($qb->expr()->in('photo.id',$ids));

        return $qb->getQuery()->getResult();
    }
}

<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Service\EntityRepositoryService;

class LocalAuthorPhotoService
{
    private $repository;
    private $service;

    public function __construct( EntityRepositoryService $service )
    {
        $this->service = $service;

        $this->repository = $service->getRepository('BugglMainBundle:LocalAuthorPhoto');
    }

    public function search( $limits = array('page' => 1, 'limit' => 0), $filters = array('country'=>0, 'city'=>0) )
    {

        $country = $filters['country'];
        $city = $filters['city'];
        $page = $limits['page'];
        $limit = $limits['limit'];

        //country == 0
        if($country == 0){
            $photo = $this->repository->findAll( $limit, $page );
            $count = count($photo);
        }
        else if( $country > 0 && $city == 0 ){
            $photo = $this->repository->findAllByCountry( $country, $limit, $page );
            $count = $this->repository->countAllByCountry( $country );
        }
        else{
            $photo = $this->repository->findAllByCity( $city, $limit, $page );
            $count = $this->repository->countAllByCity( $city );
        }


        return array( 'result' => $this->__toArray( $photo ), 'count' => $count );
    }

    private function __toArray( $objects )
    {
        $json = array();

        foreach( $objects as $object ){
            $json[] = array(
                    'id' => $object->getId(),
                    'source' => $object->getImageWebPath(),
                    'caption' => $object->getCaption()
                );
        }

        return $json;
    }
}
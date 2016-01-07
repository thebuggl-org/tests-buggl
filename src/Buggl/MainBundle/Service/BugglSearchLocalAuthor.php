<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Service\EntityRespositoryService;

/**
 * Class used to search local authors
 *
 * @author    Vincent Farly G. Taboada <farly.taboada@goabroad.com>
 * @copyright 2013 (c) Buggl.com
 */
class BugglSearchLocalAuthor
{
    /**
     * @var EntityRepositoryService
     */
    private $repositoryService;

    /**
     * @var EntityRepository
     */
    private $repository;

    /**
     * @param EntityRepositoryService $repositoryService
     */
    public function __construct( EntityRepositoryService $repositoryService )
    {
        $this->repositoryService = $repositoryService;
        $this->repository = null;
    }

    /**
     * @param string $string
     *
     * @return self
     */
    public function setRepository($string='')
    {
        $this->repository = $this->repositoryService->getRepository($string);

        return $this;
    }

    /**
     * search local authors based of the parameters and filters given
     * @param array $params  []
     * @param array $filters []
     *
     * @return array
     */
    public function searchLocalAuthor( $params, $filters )
    {
        $city = isset($params['city']) ? $params['city'] : 0;
        $country = isset($params['country']) ? $params['country'] : 0;

        $name = isset($filters['name']) ? $filters['name'] : '';
        $page = isset($filters['page']) ? $filters['page'] : 1;
        $limit = isset($filters['limit']) ? $filters['limit'] : 0;

        $search = array();

        //search all
        if ($country == 0 && $city == 0) {
            $data = $this->repository->findAllWithFilters($limit, ($page-1)*$limit, $name);

            $totalCount = count($data);

            $search = array( 'count' => $totalCount, 'result' => $data );
        } else if ($country > 0 && $city == 0) {
            //search by country
            $this->setRepository('BugglMainBundle:Location');
            $data = $this->repository->findAllByCountry($country, $limit, ($page-1)*$limit, $name);

            $totalCount = count($data);

            $authors = array();
            foreach ($data as $each) {
                $authors[] = $each->getLocalAuthor();
            }

            $search = array( 'count' => $totalCount, 'result' => $authors );
        } else if ($country > 0 && $city > 0) {
            //search by  city
            $this->setRepository('BugglMainBundle:Location');
            $data = $this->repository->findAllByCity($city, $limit, ($page-1)*$limit, $name);

            $totalCount = count($data);

            $authors = array();
            foreach ($data as $each) {
                $authors[] = $each->getLocalAuthor();
            }

            $search = array( 'count' => $totalCount, 'result' => $authors );
        }

        return $search;
    }
}
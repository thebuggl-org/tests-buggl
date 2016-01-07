<?php

namespace Buggl\MainBundle\Service;

use Doctrine\ORM\EntityManager;

class EntityRepositoryService
{
    private $entityManager;

    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function getRepository($entityClass)
    {
        return $this->entityManager->getRepository($entityClass);
    }
}
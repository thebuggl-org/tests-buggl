<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\TripTheme;
use Doctrine\ORM\EntityManager;

/**
 * TripThemeService: used to add and upadate trip themes
 *
 * @author    Vincent Farly G. Tabaods <farly.taboada@goabroad.com>
 * @copyright 2013 (c) Buggl.com
 */
class TripThemeService
{
    /**
     * @var EntityManager
     */
    private $entityManager;


    /**
     * constructor
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * updates trip themes name
     * @param Integer $id   []
     * @param String  $name []
     *
     * @return Array
     */
    public function update($id, $name)
    {
        $tripTheme = $this->entityManager->find('BugglMainBundle:TripTheme', $id);

        if ($tripTheme) {
            $tripTheme->setName($name);

            $this->entityManager->persist($tripTheme);
            $this->entityManager->flush();

            $data = array('success' => true, 'name' => $tripTheme->getName());
        } else {
            $data = array('success' => false);
        }

        return $data;
    }

    /**
     * @param Array $data
     *
     * @return Array
     */
    public function add($data = array())
    {

        $tripTheme = new TripTheme();
        $tripTheme->setName($data['name']);
        $tripTheme->setStatus($data['status'] ? 1: 0);
        $tripTheme->setIsDefault(1);

        $this->entityManager->persist($tripTheme);
        $this->entityManager->flush();

        return array('success' => true);
    }
}
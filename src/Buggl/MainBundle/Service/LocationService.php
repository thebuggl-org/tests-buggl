<?php

namespace Buggl\MainBundle\Service;

use Doctrine\ORM\EntityManager;
use Symfony\Bundle\TwigBundle\Debug\TimedTwigEngine;

class LocationService
{
    private $entityManager;
    private $templating;
    private $data;
    private $isEdit;

    public function __construct(EntityManager $entityManager, $templating)
    {
        $this->entityManager = $entityManager;
        $this->templating = $templating;
    }

    public function initCountry($data, $isEdit)
    {
        $this->isEdit = $isEdit;
        $this->data = $data;

        return $this;
    }

    public function initCity($data, $isEdit)
    {
        $this->isEdit = $isEdit;
        $this->data = $data;

        return $this;
    }

    public function saveCity()
    {
        if($this->isEdit){
            $this->updateCity();
        }
        else{
            $this->addCity();
        }
    }

    public function saveCountry()
    {
        if($this->isEdit){
            $this->updateCountry();
        }
        else{
            $this->addCountry();
        }

        return $this;
    }

    public function initCategory($data,$createNew,$id,$photo = null)
    {
        $this->data = $data;
        $this->createNew = $createNew;
        $this->id = $id;
        $this->photo = $photo;

        return $this;
    }

    public function saveCategory()
    {
        if($this->createNew){
            $categoryToCountry = new \Buggl\MainBundle\Entity\CategoryToCountry();
        }
        else{
            $categoryToCountry = $this->entityManager->find('BugglMainBundle:CategoryToCountry',$this->id);
        }

        $categoryToCountry->setCategory($this->data->getCategory());
        $categoryToCountry->setCountry($this->data->getCountry());

        if(!is_null($this->photo)){
            $categoryToCountry->setCategoryToCountryPhoto($this->photo);
        }

        $this->entityManager->persist($categoryToCountry);
        $this->entityManager->flush();

        $html  = $this->templating->render('BugglMainBundle:Admin\Location:categoryList.html.twig',array('object' => $categoryToCountry));

        return $html;
    }

    private function updateCity()
    {
        $object = $this->entityManager
                       ->find('BugglMainBundle:City',$this->isEdit);

        $object->setName($this->data['name']);
        $object->setCountry($this->data['country']);
        $object->setLat($this->data['lat']);
        $object->setLong($this->data['long']);

        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }

    private function addCity()
    {
        $object = new \Buggl\MainBundle\Entity\City();

        $object->setName($this->data['name']);
        $object->setCountry($this->data['country']);
        $object->setLat($this->data['lat']);
        $object->setLong($this->data['long']);

        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }

    private function updateCountry()
    {
        $object = $this->entityManager
                        ->find('BugglMainBundle:Country',$this->isEdit);

        $object->setName($this->data['name']);
        $object->setContinent($this->data['continent']);

        $this->entityManager->persist($object);
        $this->entityManager->flush();

        // return $this->templating->renderView('BugglMainBundle:Admin\Location:countryList.html.twig',array('list'=>$object));
    }

    private function addCountry()
    {
        $object = new \Buggl\MainBundle\Entity\Country();

        $object->setName($this->data['name']);
        $object->setContinent($this->data['continent']);

        $this->entityManager->persist($object);
        $this->entityManager->flush();
    }
}
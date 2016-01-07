<?php

namespace Buggl\MainBundle\Service;

use Buggl\MainBundle\Entity\Category;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Doctrine\ORM\EntityManager;
use Buggl\MainBundle\Helper\BugglConstant;

class CategoryService
{
    private $entityManager;
    private $dispatcher;
    private $constants;

    public function __construct(EntityManager $entityManager, BugglConstant $constants, $dispatcher)
    {
        $this->entityManager = $entityManager;
        $this->dispatcher = $dispatcher;
        $this->constants = $constants;
    }

    public function update($id,$name)
    {
        $category = $this->entityManager->find('BugglMainBundle:Category',$id);

        if($category){
            $search = $category->getName();

            $category->setName($name);

            $this->entityManager->persist($category);
            $this->entityManager->flush();

            $event = new \Buggl\MainBundle\Event\UpdateEguideEvent($category,$search);

            $this->dispatcher->dispatch('buggl.eguide_update_category',$event);

            $data = array('success' => true, 'name' => $category->getName());
        }
        else{
            $data = array('success' => false);
        }

        return $data;
    }

    public function add($data = array())
    {
        $isPublished = $data['is_published'] ?
            $this->constants->get('published_category') :
            $this->constants->get('unpublished_category');

        $category = new Category();
        $category->setName($data['name']);
        $category->setIsPublished($isPublished);
        $category->setIsDefault($this->constants->get('admin_category'));

        $this->entityManager->persist($category);
        $this->entityManager->flush();

        return array('success' => true);
    }
}
<?php

namespace Buggl\MainBundle\EventListener;

use Buggl\MainBundle\Event\UpdateEguideEvent;

class EguideListener
{
	private $entityManager;
	
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}
	
	public function updateEguideCategoryNames(UpdateEguideEvent $event)
	{
		$category = $event->getCategory();
		$search = $event->getSearchString();

		$repository = $this->entityManager->getRepository('BugglMainBundle:EGuide');

		$eguides = $repository->findByCategoryName($search);

		foreach($eguides as $each){
			$categories = $each->getCategoryNames();

			$categories = str_replace($search, $category->getName(), $categories);
			$each->setCategoryNames($categories);

			$this->entityManager->persist($each);
		}

		$this->entityManager->flush();
	}
}
<?php

namespace Buggl\MainBundle\EventListener;

use Buggl\MainBundle\Event\EguideEvent;
use Buggl\MainBundle\Entity\Keyword;

/**
 * invoked everytime an eguide is updated
 *
 * @author Vincent Farly Taboada <farly.taboda@goabroad.com>
 */
class FreeSearchEventListener
{
	/**
	 * @var EntityManager
	 */
	private $entityManager;

	/**
	 * characters to be removed
	 * @var array
	 */
	private $removeChars = array('#','`','"',"'",'!','“','”','.',',');


	/**
	 * injects entity manager class
	 * @param EntityManager $entityManager
	 */
	public function __construct($entityManager)
	{
		$this->entityManager = $entityManager;
	}


	/**
	 * method invoked everytime an event is dispatched(guide updates)
	 * @param  EguideEvent $event
	 */
	// public function updateFreeSearchField(EguideEvent $event)
	// {
	// 	$eguide = $event->getEguide();

	// 	$repository = $this->entityManager->getRepository('BugglMainBundle:Keyword');

	// 	$keywords = $repository->findByEGuide($eguide);

	// 	foreach ($keywords as $keyword) {
	// 		$this->entityManager->remove($keyword);
	// 	}

	// 	/**
	// 	 * needs improvement
	// 	 * @var string
	// 	 */
	// 	$title = str_replace($this->removeChars, '', strtolower($eguide->getPlainTitle()));

	// 	$keyword = new Keyword();
	// 	$keyword->setKeyword($title);
	// 	$keyword->setEGuide($eguide);

	// 	$this->entityManager->persist($keyword);

	// 	$categories = $eguide->getCategories();

	// 	foreach ($categories as $category ) {
	// 		$categoryName = strtolower($category->getName());

	// 		$keyword = new Keyword();
	// 		$keyword->setKeyword($categoryName);
	// 		$keyword->setEGuide($eguide);

	// 		$this->entityManager->persist($keyword);
	// 	}

	// 	$country = strtolower($eguide->getCountry()->getName());

	// 	$keyword = new Keyword();
	// 	$keyword->setKeyword($country);
	// 	$keyword->setEGuide($eguide);
	// 	$this->entityManager->persist($keyword);

	// 	$this->entityManager->flush();
	// }

	/**
	 * method invoked everytime an event is dispatched(guide updates)
	 * @param  EguideEvent $event
	 */
	public function updateFreeSearchField(EguideEvent $event)
	{
		$eguide = $event->getEguide();

		$keywords = array();

		/**
		 * needs improvement
		 * @var string
		 */

		$categories = $eguide->getCategories();

		foreach ($categories as $category ) {
			$categoryName = strtolower($category->getName());
			$keywords[] = 'activity:'.$categoryName;
		}
		$country = strtolower($eguide->getCountry()->getName());

		$locations = $eguide->getLocations();

		foreach ($locations as $location) {
			$address = 'location:'.$location->getAddress();
			$keywords[] = $address;
		}

		$keywords[] = $country;
		$keywords = implode(" ", $keywords);

		$eguide->setFreeSearch($keywords);

		$this->entityManager->persist($eguide);
		$this->entityManager->flush();
	}
}
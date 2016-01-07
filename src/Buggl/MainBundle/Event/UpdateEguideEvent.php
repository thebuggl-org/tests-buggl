<?php

namespace Buggl\MainBundle\Event;

use Symfony\Component\EventDispatcher\Event;
use Buggl\MainBundle\Entity\Category;

class UpdateEguideEvent extends Event
{
	private $category;
	private $name;

	public function __construct(Category $category, $name)
	{
		$this->category = $category;
		$this->name = $name;
	}

	public function getCategory()
	{
		return $this->category;
	}

	public function getSearchString()
	{
		return $this->name;
	}
}
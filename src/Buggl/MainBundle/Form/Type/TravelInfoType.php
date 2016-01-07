<?php

namespace Buggl\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TravelInfoType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('whyAmITheBest', 'textarea', array('max_length' => 1000, 'label' => 'Why am I the best local?', 'required' => false));
		$builder->add('howILoveToTravel', 'textarea', array('max_length' => 1000, 'label' => 'How I love to travel?', 'required' => false));
		$builder->add('worstTravelExp', 'textarea', array('max_length' => 1000, 'label' => 'My best travel experience', 'required' => false));
		$builder->add('bestTravelExp', 'textarea', array('max_length' => 1000, 'label' => 'My worst travel experience', 'required' => false));
		$builder->add('localAuthorId', 'hidden', array('required' => true,'mapped' => false));
	}
	
	public function getName()
	{
		return "TravelInfo";
	}
}
<?php

namespace Buggl\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class LocalInterestType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('title', 'text', array('max_length' => 250, 'label' => 'Title'));
		$builder->add('content', 'textarea', array('max_length' => 1000, 'label' => 'Content'));
		$builder->add('imageFilename', 'text');
		$builder->add('localAuthorId', 'hidden', array('required' => true,'mapped' => false));
	}
	
	public function getName()
	{
		return "LocalInterest";
	}
}
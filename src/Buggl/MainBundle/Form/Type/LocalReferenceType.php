<?php

namespace Buggl\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraint\Email;

class LocalReferenceType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text', array('max_length' => 300,));
		$builder->add('referenceEmail', 'email', array('max_length' => 300));
		$builder->add('comment', 'textarea');
	}
	
	public function getName()
	{
		return "LocalReference";
	}
}
<?php

namespace Buggl\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class StaticContentType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('content', 'textarea', array('required' => false,'attr' => array('cols' => '5', 'rows' => '5'),));
		$builder->add('url', 'text', array('required' => true));
		$builder->add('title', 'text', array('required' => true));
	}
	
	public function getName()
	{
		return "StaticContent";
	}
}
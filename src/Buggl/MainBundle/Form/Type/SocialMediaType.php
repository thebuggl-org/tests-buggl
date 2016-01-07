<?php

namespace Buggl\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SocialMediaType extends AbstractType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		//$builder->add('fbUrl', 'url', array('max_length' => 500, 'label' => 'Facebook Profile Url', 'required' => false));
		//$builder->add('twitterUrl', 'url', array('max_length' => 500, 'label' => 'Twitter Profile Url', 'required' => false));
		$builder->add('youtubeUrl', 'url', array('max_length' => 500, 'label' => 'YouTube Page Url', 'required' => false));
	}
	
	public function getName()
	{
		return "SocialMedia";
	}
}
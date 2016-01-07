<?php

namespace Buggl\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormError;

class LocalAuthorType extends AbstractType
{
	private $extraData;
	private $buildFor;
	
	private $builds = array(
		0 => "Registration",
		1 => "NameUpdate",
		2 => "EmailUpdate",
		3 => "LocationUpdate",
		4 => "PasswordUpdate",
		5 => "RegistrationViaFb",
		6 => "RegistrationViaTwitter",
		7 => "RegistrationViaGooglePlus",
		8 => "GuideInfoUpdate",
	);
	
	public function __construct($buildFor = 0, $extraData = array())
	{
		$this->extraData = $extraData;
		$this->buildFor = $buildFor;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$buildMethod = 'addFieldsFor'.$this->builds[$this->buildFor];
		
		$this->$buildMethod($builder,$options);
	}
	
	private function addFieldsForRegistration(FormBuilderInterface $builder, array $options)
	{
		$this->addFieldsForNameUpdate($builder,$options);
		$this->addFieldsForEmailUpdate($builder,$options);
		$this->addFieldsForLocationUpdate($builder,$options);
		$this->addFieldsForPasswordUpdate($builder,$options);
		$this->addFieldsForAccountType($builder,$options);
	}
	
	private function addFieldsForRegistrationViaFb(FormBuilderInterface $builder, array $options)
	{
		$this->addFieldsForNameUpdate($builder,$options);
		$this->addFieldsForLocationUpdate($builder,$options);
		$this->addFieldsForAccountType($builder,$options);
		$builder->add('email', 'email', array('max_length' => 100, 'label' => 'Email', 'data' => $this->extraData['fbEmail']));
		
		$builder->add('fbId', 'hidden', array('data' => $this->extraData['fbId'], 'mapped' => false));
		$builder->add('fbUrl', 'hidden', array('data' => $this->extraData['fbUrl'], 'mapped' => false));
		$builder->add('fbAccessToken', 'hidden', array('data' => $this->extraData['fbAccessToken'], 'mapped' => false));
		$builder->add('fbEmail', 'hidden', array('data' => $this->extraData['fbEmail'], 'mapped' => false));
		$builder->add('fbBirthday', 'hidden', array('data' => $this->extraData['fbBirthday'], 'mapped' => false));
		$builder->add('fbWork', 'hidden', array('data' => $this->extraData['fbWork'], 'mapped' => false));
	}
	
	private function addFieldsForRegistrationViaTwitter(FormBuilderInterface $builder, array $options)
	{
		$this->addFieldsForNameUpdate($builder,$options);
		$this->addFieldsForEmailUpdate($builder,$options);
		$this->addFieldsForLocationUpdate($builder,$options);
		$this->addFieldsForAccountType($builder,$options);
		//$builder->add('betaToken', 'text', array('max_length' => 100, 'label' => 'Beta Token', 'mapped' => false));
		
		$builder->add('twitterId', 'hidden', array('data' => $this->extraData['twitterId'], 'mapped' => false));
		$builder->add('twitterUrl', 'hidden', array('data' => $this->extraData['twitterUrl'], 'mapped' => false));
		$builder->add('twitterAccessToken', 'hidden', array('data' => $this->extraData['twitterAccessToken'], 'mapped' => false));
		$builder->add('twitterTokenSecret', 'hidden', array('data' => $this->extraData['twitterTokenSecret'], 'mapped' => false));
	}
	
	private function addFieldsForRegistrationViaGooglePlus(FormBuilderInterface $builder, array $options)
	{
		$this->addFieldsForNameUpdate($builder,$options);
		$this->addFieldsForEmailUpdate($builder,$options);
		$this->addFieldsForLocationUpdate($builder,$options);
		$this->addFieldsForAccountType($builder,$options);
		
		$builder->add('googlePlusId', 'hidden', array('data' => $this->extraData['googlePlusId'], 'mapped' => false));
		$builder->add('googlePlusUrl', 'hidden', array('data' => $this->extraData['googlePlusUrl'], 'mapped' => false));
		$builder->add('googlePlusAccessToken', 'hidden', array('data' => $this->extraData['googlePlusAccessToken'], 'mapped' => false));
		$builder->add('googlePlusRefreshToken', 'hidden', array('data' => $this->extraData['googlePlusRefreshToken'], 'mapped' => false));
	}
	
	private function addFieldsForPasswordUpdate(FormBuilderInterface $builder, array $options)
	{
		$builder->add('password', 'repeated', array('type' => 'password', 'options' => array('max_length' => 20), 'invalid_message' => 'Passwords did not match!'));
	}
	
	private function addFieldsForNameUpdate(FormBuilderInterface $builder, array $options)
	{
		$builder->add('firstName', 'text', array('max_length' => 100, 'label' => 'First Name'));
		$builder->add('lastName', 'text', array('max_length' => 100, 'label' => 'First Name'));
	}
	
	private function addFieldsForEmailUpdate(FormBuilderInterface $builder, array $options)
	{
		$builder->add('email', 'repeated', array('type' => 'email', 'options' => array('max_length' => 100), 'invalid_message' => 'Email addresses did not match!'));
	}
	
	/*
	 * @deprecated
	 */
	private function addFieldsForLocationUpdateDeprecated(FormBuilderInterface $builder, array $options)
	{			
		$builder->add('location', 'entity', array('class' => 'BugglMainBundle:City', 'property' => 'name', 'group_by' => 'country.name', 'empty_value' => 'Choose a location', 'required' => false, 'mapped' => false));
		$builder->get('location')->addValidator(new CallbackValidator(function(FormInterface $form)
		{
			if(is_null($form->getData()))
		        $form->addError(new FormError('Please select a location.'));
		}));
	}
	
	private function addFieldsForLocationUpdate(FormBuilderInterface $builder, array $options)
	{			
		$builder->add('country', 'text', array('mapped' => false));
		/*$builder->get('country')->addValidator(new CallbackValidator(function(FormInterface $form)
		{
			if(is_null($form->getData()))
		        $form->addError(new FormError('Please select a country.'));
		}));*/
		
		$builder->add('city', 'text', array('mapped' => false));
		/*$builder->get('city')->addValidator(new CallbackValidator(function(FormInterface $form)
		{
			if(is_null($form->getData()))
		        $form->addError(new FormError('Please select a city.'));
		}));*/
	}
	
	private function addFieldsForAccountType(FormBuilderInterface $builder, array $options)
	{
		$builder->add('type', 'choice', array('choices' => array(1 => 'Insider (Writer)', 0 => 'Traveler'),'mapped' => false));
	}
	
	public function getDefaultOptions(array $options)
	{
	    return array(
	        'data_class' => 'Buggl\MainBundle\Entity\LocalAuthor',
			'cascade_validation' => true,
	    );
	}
	
	public function getName()
	{
		return 'LocalAuthor';
	}
}
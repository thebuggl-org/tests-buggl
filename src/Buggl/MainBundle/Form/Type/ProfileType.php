<?php

namespace Buggl\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Buggl\MainBundle\Form\Type\LocalAuthorType;

class ProfileType extends AbstractType
{
	private $buildFor;
	
	private $builds = array(
		'location' => 'LocationField',
		'aboutYou'  => "AboutYouField",
		'selfComment'  => "SelfCommentField",
		'profileBasicInfo' => "ProfileBasicInfoFields",
		'guideInfo' => "GuideInfoFields",
		'localStats' => "LocalStatsFields",
		'localVerified' => "LocalVerifiedFields",
		'authorPageBasicInfo' => 'AuthorPageBasicInfoFields'
	);
	
	public function __construct($buildFor = 0)
	{
		$this->buildFor = $buildFor;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$buildMethod = 'add'.$this->builds[$this->buildFor];
		
		$this->$buildMethod($builder,$options);
	}
	
	private function addPhoneField(FormBuilderInterface $builder, array $options)
	{
		$builder->add('phone', 'text', array('max_length' => 45, 'label' => 'Phone'));
	}
	
	private function addSkypeField(FormBuilderInterface $builder, array $options)
	{
		$builder->add('skypeId', 'text', array('max_length' => 100, 'label' => 'Skype Id'));
	}
	
	private function addLocalSinceField(FormBuilderInterface $builder, array $options)
	{
		$today = date('Y');
		$years = array();
		for($i=1970;$i<$today;$i++){
			$years[$i] = $i;
		}
		$builder->add('localSince', 'choice', array('choices' => $years, 'required' => 'true'));
	}
	
	private function addInterestAndActivitiesField(FormBuilderInterface $builder, array $options)
	{
		$builder->add('interestAndActivities', 'text', array('max_length' => 250, 'label' => 'Interest and Activities'));
	}
	
	private function addAccomplishmentsField(FormBuilderInterface $builder, array $options)
	{
		$builder->add('accomplishments', 'text', array('max_length' => 1000, 'label' => 'Accomplishments'));
	}
	
	private function addBirthDateField(FormBuilderInterface $builder, array $options)
	{
		$builder->add('birthDate', 'text', array('label' => 'Birth Date'));
	}
	
	private function addAboutYouField(FormBuilderInterface $builder, array $options)
	{
		$builder->add('aboutYou', 'textarea', array('max_length' => 500, 'label' => ''));
	}
	
	private function addSelfCommentField(FormBuilderInterface $builder, array $options)
	{
		$builder->add('selfComment', 'textarea', array('max_length' => 500, 'label' => ''));
	}
	
	private function addKidsInfoField(FormBuilderInterface $builder, array $options)
	{
		$builder->add('kidsInfo', 'text', array('max_length' => 45, 'label' => 'Do you have children?'));
	}
	
	private function addFileField(FormBuilderInterface $builder, array $options)
	{
		$builder->add('file', 'file', array('label' => 'Photo'));
	}
	
	private function addWorkField(FormBuilderInterface $builder, array $options)
	{
		$builder->add('work', 'text', array('label' => 'Professional Title'));
	}
	
	// === 
	
	private function addLocationField(FormBuilderInterface $builder, array $options)
	{
		$builder->add('localAuthor', new LocalAuthorType(3));
	}
	
	private function addProfileBasicInfoFields(FormBuilderInterface $builder, array $options)
	{
		$builder->add('localAuthor', new LocalAuthorType(1));
		$this->addAboutYouField($builder,$options);
		$this->addWorkField($builder,$options);
		$this->addSelfCommentField($builder,$options);
	}
	
	private function addGuideInfoFields(FormBuilderInterface $builder, array $options)
	{
		$builder->add('localAuthor', new LocalAuthorType(3));
		$this->addBirthDateField($builder,$options);
	}
	
	private function addLocalStatsFields(FormBuilderInterface $builder, array $options)
	{
		$this->addLocalSinceField($builder,$options);
		$this->addInterestAndActivitiesField($builder,$options);
		$this->addAccomplishmentsField($builder,$options);
	}
	
	private function addLocalVerifiedFields(FormBuilderInterface $builder, array $options)
	{
		$this->addPhoneField($builder,$options);
	}
	
	private function addAuthorPageBasicInfoFields(FormBuilderInterface $builder, array $options)
	{
		$builder->add('localAuthor', new LocalAuthorType(1));
		$this->addWorkField($builder,$options);
		$this->addLocalSinceField($builder,$options);
		$this->addBirthDateField($builder,$options);
		$this->addInterestAndActivitiesField($builder,$options);
	}
	
	public function getDefaultOptions(array $options)
	{
	    return array(
			'cascade_validation' => true,
	    );
	}
	
	public function getName()
	{
		return "Profile";
	}
}
<?php

namespace Buggl\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;

class CategoryType extends AbstractType
{

    private $options;

    public function __construct($options = array())
    {
        $this->options = $options;
    }


    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('country','entity',
                    array(
                        'class' => 'BugglMainBundle:Country', 
                        'property' => 'name', 
                        'empty_value' => 'Choose County', 
                        'required' => true,
                        'error_bubbling' => true
                    ))
                ->add('category','entity',
                    array(
                        'class' => 'BugglMainBundle:Category', 
                        'property' => 'name', 
                        'empty_value' => 'Choose Category', 
                        'query_builder' => $this->getCategoryOptions(),
                        'required' => true,
                        'error_bubbling' => true
                    ))
                ->add('picFilename','file',
                    array(
                        'constraints' => new \Symfony\Component\Validator\Constraints\Image(),
                        'data_class' => null,
                        'mapped' => false
                    ));

    }
	
	public function getCategoryOptions() 
	{
		$repository = $this->options['repository'];
        return $repository->createQueryBuilder('category')
                          ->where("category.isPublished = :status")
                          ->orderBy("category.name","ASC")
                          ->setParameter('status',$this->options['status']);
	}
    
    public function getName()
    {
        return "category_to_country";
    }
}
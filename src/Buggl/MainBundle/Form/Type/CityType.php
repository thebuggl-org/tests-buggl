<?php

namespace Buggl\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('name','text',
                        array('constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()))
                ->add('country','entity',
                        array(
                            'class' => 'BugglMainBundle:Country', 
                            'property' => 'name', 'empty_value' => 'Choose Country', 
                            'required' => true
                        ))
                ->add('lat','text',
                        array('constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()))
                ->add('long','text',
                        array('constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()));
    }
    
    public function getName()
    {
        return "country";
    }
}
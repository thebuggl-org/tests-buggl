<?php

namespace Buggl\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class CountryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        

        $builder->add('name','text',
                        array('constraints' => new \Symfony\Component\Validator\Constraints\NotBlank()))
                ->add('continent','entity',
                        array(
                            'class' => 'BugglMainBundle:Continent', 
                            'property' => 'name', 'empty_value' => 'Choose Continent', 
                            'required' => true
                        ));

    }
    
    public function getName()
    {
        return "country";
    }
}
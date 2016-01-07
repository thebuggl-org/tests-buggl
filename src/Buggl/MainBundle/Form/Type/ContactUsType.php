<?php

namespace Buggl\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactUsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('email','email',array('constraints' => array(
							new \Symfony\Component\Validator\Constraints\NotBlank(array('message' => 'Required')),
							new \Symfony\Component\Validator\Constraints\Email(array('message' => 'Not a valid email address'))
				)))
                ->add('comment','textarea',array('constraints' => new \Symfony\Component\Validator\Constraints\NotBlank(array('message' => 'Required'))));
    }
    
    public function getName()
    {
        return "contactus";
    }
}
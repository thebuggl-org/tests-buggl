<?php

namespace Buggl\MainBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class PaypalInfoType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('firstName','text')
                ->add('lastName','text')
                ->add('email','email');
    }
    
    public function getName()
    {
        return "paypal_info";
    }
}
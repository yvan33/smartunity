<?php

namespace SmartUnity\AppBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ContactFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder          
        ->add('email', 'email', array('label' => 'Email'))
        ->add('subject', 'text')
        ->add('contact','textarea');

    }

    public function getName()
    {
        return 'smart_unity_app_contact';
    }


}

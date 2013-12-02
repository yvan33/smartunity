<?php

namespace SmartUnity\UtilisateurBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class ParrainageFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder            
        ->add('email', 'email', array('label' => 'Email'));
    }

    public function getName()
    {
        return 'smartunity_user_parrainage';
    }


}

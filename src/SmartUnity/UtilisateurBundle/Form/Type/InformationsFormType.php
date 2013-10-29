<?php

namespace SmartUnity\UtilisateurBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class InformationsFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
        ->add('nom')
        ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
        ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
        ->add('sexe', 'choice', array(
        'choices' => array(
            'm' => 'Masculin', 'f' => 'Féminin'),
        'expanded' => true,
//        'data' => ""
        ))
        ->add('date_naissance', 'date');
                
    }

    public function getName() {
        return 'smartunity_user_informations';
    }

}

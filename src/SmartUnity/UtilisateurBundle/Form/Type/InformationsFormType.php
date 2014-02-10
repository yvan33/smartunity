<?php

namespace SmartUnity\UtilisateurBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class InformationsFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
        ->add('nom')
        ->add('prenom', 'text', array(
            'required' => false, 
        ))                
        ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
        ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
        ->add('sexe', 'choice', array(
        'choices' => array(
            'na' =>'Non renseigné', 'm' => 'Masculin', 'f' => 'Féminin'),
//        'data' => ""
        ))
        ->add('date_naissance', 'date', array(
        'empty_value' => '',
        'widget' => 'choice',
        'years' => range(date('Y'), date('Y')-100),
        'required' => false,
        'format' =>'dd MM yyyy',
        'model_timezone' => 'Europe/Paris',
        'empty_value' => (array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'))
            ))
        ->add('telephone','text',array(
            'required'=> false,
            'label' => 'Smartphone :'
        ))
        ->add('info_plus','textarea',array(
            'required'=> false,
            'label' => 'Un peu plus sur moi :'
        ));
                
    }

    public function getName() {
        return 'smartunity_user_informations';
    }

}

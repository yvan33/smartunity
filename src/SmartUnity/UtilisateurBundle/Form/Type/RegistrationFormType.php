<?php

namespace SmartUnity\UtilisateurBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
//        parent::buildForm($builder, $options);
//        


        $builder
                ->add('nom','text', array('required'=>false))
                ->add('prenom','text', array('required'=>false))
                ->add('email', 'email', array('label' => 'Adresse e-mail'))
                ->add('username', null, array('label' => 'Pseudonyme'))
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => 'Mot de passe'),
                    'second_options' => array('label' => 'Veuillez le retaper'),
                    'invalid_message' => 'fos_user.password.mismatch'))
                ->add('sexe', 'choice', array(
                    'choices' => array(
                        'na' => 'Non défini', 'm' => 'Masculin', 'f' => 'Féminin'),
                    'expanded' => false,
                    'data' => 'na',
                ))
                ->add('date_naissance', 'date', array(
                    'empty_value' => '',
                    'widget' => 'choice',
                    'years' => range(date('Y'), date('Y') - 100),
                    'required' => false,
                    'format' =>'dd MM yyyy',
                    'model_timezone' => 'Europe/Paris',
                    'empty_value' => (array('year' => 'Année', 'month' => 'Mois', 'day' => 'Jour'))
                    
                ))
                ->add('telephone', 'text', array(
                    'required' => false,
                    'label' => 'Modèle de smartphone'
               ))
                ->add('info_plus','textarea',array(
                    'required'=> false,
                    'label' => 'Un peu plus sur moi'
                ))
                ->add('parrain', 'hidden_entity', array(
                    'entity' => 'SmartUnityAppBundle:membre',
                ))
        ;
    }

    public function getName() {
        return 'smartunity_user_registration';
    }

}

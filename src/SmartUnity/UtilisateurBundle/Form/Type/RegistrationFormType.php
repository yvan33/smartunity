<?php

namespace SmartUnity\UtilisateurBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;
use SmartUnity\UtilisateurBundle\Form\Type\HiddenEntity;

class RegistrationFormType extends BaseType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
//        parent::buildForm($builder, $options);
//        
//Liste des modèles de smartphones provisoire
        $appareil_choices = array(
        'Samsung' => array(
            '1' => array(
                '1' => 'Galaxy S3',
                '2' => 'Galaxy S4',
                        )),
         'Apple' => array(
            '5' => array(
                '3' => 'iphone5',
                '12' => 'iphone5S',
            ))
        );

        $builder
                ->add('nom')
                ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
                ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
                ->add('plainPassword', 'repeated', array(
                    'type' => 'password',
                    'options' => array('translation_domain' => 'FOSUserBundle'),
                    'first_options' => array('label' => 'form.password'),
                    'second_options' => array('label' => 'form.password_confirmation'),
                    'invalid_message' => 'fos_user.password.mismatch'))
                ->add('sexe', 'choice', array(
                    'choices' => array(
                        'na' => 'Non défini', 'm' => 'Masculin', 'f' => 'Féminin'),
                    'expanded' => false,
                    'data' => 'na'
                ))
                ->add('date_naissance', 'date', array(
                    'empty_value' => '',
                    'widget' => 'choice',
                    'years' => range(date('Y') - 100, date('Y')),
                    'required' => false
                ))
//                ->add('ville', 'text', array(
//                    'label' => 'Ville',
//                    'required' => false
//                    'class' => 'SmartUnityAppBundle:ville',
//                    'property' => 'nom',
//                    'query_builder' => function(\Doctrine\ORM\EntityRepository $er) {
//                                       return $er->createQueryBuilder('u')
//                                       ->orderBy('u.nom', 'ASC');},
//                ))
//                ->add('appareils', 'text', array(
//                    'type' => 'choice',
//                    'required' => false,
//                    'label' => 'Smartphone(s) en possession',
//                    'options' => array(
//                        'choices' => $appareil_choices,
//                    ),
//                    'allow_add' => true,
//                    'prototype' => true
//                ))
                ->add('telephone','text',array(
                    'required'=> false,
                ))
        
                ->add('pref_mp', 'choice', array(
                    'label' => 'Recevoir les notifications des messages privés',
                    'choices' => array(
                        '1' => 'Oui', '0' => 'Non'),
                    'expanded' => true,
                    'data' => 1
                ))
                ->add('pref_smartcafe', 'choice', array(
                    'label' => 'Recevoir les notifications du Smart\'Café',
                    'choices' => array(
                        '1' => 'Oui', '0' => 'Non'),
                    'expanded' => true,
                    'data' => 1
                ))
                ->add('pref_comm', 'choice', array(
                    'label' => 'Recevoir les notifications de la communauté',
                    'choices' => array(
                        '1' => 'Oui', '0' => 'Non'),
                    'expanded' => true,
                    'data' => 1
                ))
                ->add('pref_rep', 'choice', array(
                    'label' => 'Recevoir les notifications lorsqu\'une réponse m\'est proposée',
                    'choices' => array(
                        '1' => 'Oui', '0' => 'Non'),
                    'expanded' => true,
                  'data' => 1
                ))
                ->add('pref_repValidee', 'choice', array(
                    'label' => 'Recevoir une notification lorsque mes réponses sont validées',
                    'choices' => array(
                        '1' => 'Oui', '0' => 'Non'),
                    'expanded' => true,
                    'data' => 1
                ))
                ->add('pref_repCertifiee', 'choice', array(
                    'label' => 'Recevoir une notification lorsque mes réponses sont certifiées',
                    'choices' => array(
                        '1' => 'Oui', '0' => 'Non'),
                    'expanded' => true,
                    'data' => 1
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

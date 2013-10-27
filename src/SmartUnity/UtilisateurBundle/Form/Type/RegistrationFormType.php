<?php

namespace SmartUnity\UtilisateurBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        parent::buildForm($builder, $options);
    
        $builder
            ->add('nom') 
            ->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
            ->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
                'options' => array('translation_domain' => 'FOSUserBundle'),
                'first_options' => array('label' => 'form.password'),
                'second_options' => array('label' => 'form.password_confirmation'),
                'invalid_message' => 'fos_user.password.mismatch' ))
            
            ->add('sexe','choice',array(
                    'choices' => array(
                        '0'=> '', '1' => 'Masculin', '2' => 'Féminine'),
                    'expanded' => false,
                    'data' => 0
                       ))
            ->add('date_naissance', 'date')
            ->add('pref_mp', 'choice', array(
                'choices' => array(
                '1' => 'Oui', '0' => 'Non'),
                'expanded' => true,
                'data' => 1
                ))
            ->add('pref_smartcafe', 'choice', array(
                'label' => 'Test',
                'choices' => array(
                '1' => 'Oui', '0' => 'Non'),
                'expanded' => true,
                'data' => 1    
            ))
                
                
            ->add('pref_comm', 'choice', array(
                'label' => 'Test',
                'choices' => array(
                '1' => 'Oui', '0' => 'Non'),
                'expanded' => true,
                'data' => 1    
            ))
            ->add('pref_rep', 'choice', array(
                'label' => 'Test',
                'choices' => array(
                '1' => 'Oui', '0' => 'Non'),
                'expanded' => true,
                'data' => 1    
            ))
             ->add('pref_repValidee', 'choice', array(
                'label' => 'Test',
                'choices' => array(
                '1' => 'Oui', '0' => 'Non'),
                'expanded' => true,
                'data' => 1    
            ))
                
            ->add('pref_repCertifiee', 'choice', array(
                'label' => 'Test',
                'choices' => array(
                '1' => 'Oui', '0' => 'Non'),
                'expanded' => true,
                'data' => 1    
            ))
                
            ;
     
    }

    public function getName()
    {
        return 'smartunity_user_registration';
    }
}

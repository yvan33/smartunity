<?php

namespace SmartUnity\UtilisateurBundle\Form\Type;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use SmartUnity\AppBundle\Entity\membre;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormTypeInterface;

class PreferenceFormType extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        parent::buildForm($builder, $options);
    
        $builder 
            ->add('pref_mp', 'choice', array(
                'label' => 'Recevoir les notifications des messages privés',
                'choices' => array(
                '1' => 'Oui', '0' => 'Non'),
                'expanded' => true,
                // 'data' => membre.pref_mp
                ))          
            ->add('pref_smartcafe', 'choice', array(
                'label' => 'Recevoir les notifications du Smart\'Café',
                'choices' => array(
                '1' => 'Oui', '0' => 'Non'),
                'expanded' => true,
                // 'data' => membre.pref_smartcafe   
            ))
                
                
            ->add('pref_comm', 'choice', array(
                'label' => 'Recevoir les notifications de la communauté',
                'choices' => array(
                '1' => 'Oui', '0' => 'Non'),
                'expanded' => true,
                // 'data' => membre.pref_comm    
            ))
            ->add('pref_rep', 'choice', array(
                'label' => 'Recevoir les notifications lorsqu\'une réponse m\'est proposée',
                'choices' => array(
                '1' => 'Oui', '0' => 'Non'),
                'expanded' => true,
                // 'data' => membre.pref_rep   
            ))
             ->add('pref_repValidee', 'choice', array(
                'label' => 'Recevoir une notification lorsque mes réponses sont validées',
                'choices' => array(
                '1' => 'Oui', '0' => 'Non'),
                'expanded' => true,
                // 'data' => membre.pref_repValidee   
            ))
                
            ->add('pref_repCertifiee', 'choice', array(
                'label' => 'Recevoir une notification lorsque mes réponses sont certifiées',
                'choices' => array(
                '1' => 'Oui', '0' => 'Non'),
                'expanded' => true,
                // 'data' => membre.pref_repCertifiee    
            ))
               
                
            ;
            
    }

    public function getName()
    {
        return 'smartunity_user_preference';
    }

   
}

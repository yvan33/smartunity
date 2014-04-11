<?php

namespace SmartUnity\QuestionReponseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FiltresRepondreFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder            
                ->setMethod('GET')
                ->add('marque', 'entity', array(
                    'class' => 'SmartUnityAppBundle:marque',
                    'property' => 'nom',
                    'required' => false,
                    'empty_data' => NULL))
                ->add('os', 'entity', array(
                    'class' => 'SmartUnityAppBundle:os',
                    'property' => 'nom',
                    'required' => false,
                    'empty_data' => NULL))
                ->add('typeQuestion', 'entity', array(
                    'class' => 'SmartUnityAppBundle:typeQuestion',
                    'property' => 'nom',
                    'required' => false))
                 ->add('motCle', 'text', array(
                     'mapped' => false,
                     'required' => false));
    }

    public function getName()
    {
        return 'smartunity_filtres_repondre';
    }


}

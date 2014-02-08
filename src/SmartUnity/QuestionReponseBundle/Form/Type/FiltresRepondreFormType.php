<?php

namespace SmartUnity\QuestionReponseBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class FiltresRepondreFormType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder            
                ->add('marque', 'entity', array(
                    'class' => 'SmartUnityAppBundle:marque',
                    'property' => 'nom',
                    'required' => false,
                    'empty_value' => 'Choisissez',
                    'empty_data' => NULL))
                ->add('os', 'entity', array(
                    'class' => 'SmartUnityAppBundle:os',
                    'property' => 'nom',
                    'required' => false,
                    'empty_value' => 'Choisissez',
                    'empty_data' => NULL))
                ->add('typeQuestion', 'entity', array(
                    'class' => 'SmartUnityAppBundle:typeQuestion',
                    'property' => 'nom',
                    'empty_value' => 'Choisissez une option',
                    'required' => true))
                 ->add('motCle', 'text', array('mapped' => false));
    }

    public function getName()
    {
        return 'smartunity_filtres_repondre';
    }


}

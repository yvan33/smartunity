<?php

namespace SmartUnity\UtilisateurBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;
use FOS\UserBundle\Form\Type\RegistrationFormType as BaseType;

class RegistrationFormType extends BaseType
{
public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        // Ajoutez vos champs ici, revoilà notre champ *location* :
        $builder->add('nom');
        $builder->add('sexe');
     

    }

    public function getName()
    {
        return 'smartunity_user_registration';
    }
}

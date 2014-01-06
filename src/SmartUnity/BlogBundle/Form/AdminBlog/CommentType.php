<?php

namespace SmartUnity\BlogBundle\Form\AdminBlog;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Form\CallbackValidator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\RegexValidator;

class CommentType extends AbstractType
{
    protected $usage;
    
    public function __construct() {
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('ip','hidden')
            ->add('comment', null, array('label' => 'form.comment.comment', 'translation_domain' => 'MvBlogBundle'))
        ;
        if($this->usage === 'admin'){
            $builder->add('token')
                    ->add('ip')
                    ->add('publied', null, array('label' => 'form.comment.published', 'translation_domain' => 'MvBlogBundle'))
            ;
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        // $resolver->setDefaults(array(
        //     'data_class' => 'Mv\BlogBundle\Entity\AdminBlog\Comment'
        // ));
    }

    public function getName()
    {
        return 'smartunity_blogbundle_adminblog_commenttype';
    }
}

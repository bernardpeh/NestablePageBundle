<?php

namespace Songbird\NestablePageBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageMetaType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('page_title')
            ->add('menu_title')
            ->add('locale')
            ->add('short_description')
            ->add('content')
            ->add('page')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Songbird\NestablePageBundle\Entity\PageMeta'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'songbird_nestablepagebundle_pagemeta';
    }
}

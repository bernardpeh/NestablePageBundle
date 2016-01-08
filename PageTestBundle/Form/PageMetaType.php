<?php

namespace Bpeh\NestablePageBundle\PageTestBundle\Form;

use Bpeh\NestablePageBundle\Form\PageMetaType as BasePageMetaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageMetaType extends BasePageMetaType
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
            'data_class' => 'Bpeh\NestablePageBundle\PageTestBundle\Entity\PageMeta'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bpeh_nestablepagebundle_pagetestbundle_pagemeta';
    }
}

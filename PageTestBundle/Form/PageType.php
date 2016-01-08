<?php

namespace Bpeh\NestablePageBundle\PageTestBundle\Form;

use Bpeh\NestablePageBundle\Form\PageMetaType as BasePageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PageType extends BasePageType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('slug')
            ->add('test_hidden')
            ->add('isPublished')
            ->add('sequence')
            ->add('parent')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Bpeh\NestablePageBundle\PageTestBundle\Entity\Page'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'bpeh_nestablepagebundle_pagetestbundle_page';
    }
}

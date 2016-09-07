<?php

namespace Bpeh\NestablePageBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('bpeh_nestable_page');
        // Here you should define the parameters that are allowed to
        // configure your bundle. See the documentation linked above for
        // more information on that topic.
        $rootNode
            ->children()
                ->scalarNode('page_entity')->defaultValue('Bpeh\NestablePageBundle\PageTestBundle\Entity\Page')->end()
                ->scalarNode('pagemeta_entity')->defaultValue('Bpeh\NestablePageBundle\PageTestBundle\Entity\PageMeta')->end()
                ->scalarNode('page_form_type')->defaultValue('Bpeh\NestablePageBundle\PageTestBundle\Form\PageType')->end()
                ->scalarNode('pagemeta_form_type')->defaultValue('Bpeh\NestablePageBundle\PageTestBundle\Form\PageMetaType')->end()
            ->end()
        ;
        return $treeBuilder;
    }
}

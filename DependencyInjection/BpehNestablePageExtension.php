<?php

namespace Bpeh\NestablePageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class BpehNestablePageExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $container->setParameter( 'bpeh_nestable_page.page_entity', $config[ 'page_entity' ]);
        $container->setParameter( 'bpeh_nestable_page.pagemeta_entity', $config[ 'pagemeta_entity' ]);
        $container->setParameter( 'bpeh_nestable_page.page_type', $config[ 'page_type' ]);
        $container->setParameter( 'bpeh_nestable_page.pagemeta_type', $config[ 'pagemeta_type' ]);

        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.xml');
    }
}

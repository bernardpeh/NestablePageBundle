<?php

namespace Bpeh\NestablePageBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

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
        $container->setParameter( 'bpeh_nestable_page.page_form_type', $config[ 'page_form_type' ]);
        $container->setParameter( 'bpeh_nestable_page.pagemeta_form_type', $config[ 'pagemeta_form_type' ]);
	    $container->setParameter( 'bpeh_nestable_page.page_view_list', $config[ 'page_view_list' ]);
	    $container->setParameter( 'bpeh_nestable_page.page_view_new', $config[ 'page_view_new' ]);
	    $container->setParameter( 'bpeh_nestable_page.page_view_edit', $config[ 'page_view_edit' ]);
	    $container->setParameter( 'bpeh_nestable_page.page_view_show', $config[ 'page_view_show' ]);
	    $container->setParameter( 'bpeh_nestable_page.pagemeta_view_index', $config[ 'pagemeta_view_index' ]);
	    $container->setParameter( 'bpeh_nestable_page.pagemeta_view_edit', $config[ 'pagemeta_view_edit' ]);
	    $container->setParameter( 'bpeh_nestable_page.pagemeta_view_new', $config[ 'pagemeta_view_new' ]);
	    $container->setParameter( 'bpeh_nestable_page.pagemeta_view_show', $config[ 'pagemeta_view_show' ]);
	    $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
	    $loader->load('services.yml');
    }
}

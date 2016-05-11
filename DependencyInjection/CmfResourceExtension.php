<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\ResourceBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class CmfResourceExtension extends Extension
{
    /**

     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $config = $processor->processConfiguration($configuration, $configs);
        $container->setParameter('cmf_resource.repository_configuration', $config['repositories']);
        $loader->load('resource.xml');
        $loader->load('factories.xml');
    }

    public function getNamespace()
    {
        return 'http://cmf.symfony.com/schema/dic/'.$this->getAlias();
    }
}

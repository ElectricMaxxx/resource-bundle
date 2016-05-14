<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\ResourceBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 * @author Daniel Leech <daniel@dantleech.com>
 */
class RegistryPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('cmf_resource.registry')) {
            return;
        }

        $repositoryRegistry = $container->getDefinition('cmf_resource.registry');

        $ids = $container->findTaggedServiceIds('cmf_resource.repository_factory');
        $map = [];

        foreach ($ids as $id => $attributes) {
            if (!isset($attributes[0]['alias'])) {
                throw new \InvalidArgumentException(sprintf(
                    'No "alias" attribute specified for repository service definition tag: "%s"',
                    $id
                ));
            }

            $map[$attributes[0]['alias']] = new Reference($id);
        }

        $repositoryRegistry->replaceArgument(0, $map);
    }
}

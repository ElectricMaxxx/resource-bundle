<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\ResourceBundle\Tests\Unit\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Cmf\Bundle\ResourceBundle\DependencyInjection\Compiler\RegistryPass;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class RegistryPassTest extends \PHPUnit_Framework_TestCase
{
    protected function setUp()
    {
        $this->container = new ContainerBuilder();
        $this->container->addCompilerPass(new RegistryPass());
    }

    public function testCompilerPass()
    {
        $registryDefinition = new Definition(\stdClass::class);
        $registryDefinition->setArguments([
            new Definition(),
            [],
            [],
        ]);
        $this->container->setDefinition('cmf_resource.registry', $registryDefinition);

        $repositoryDefinition = new Definition('ThisIsClass');
        $repositoryDefinition->addTag('cmf_resource.repository_factory', [
            'alias' => 'foobar',
        ]);
        $this->container->setDefinition('cmf_resource.repository_factory.test', $repositoryDefinition);
        $this->container->compile();

        $methodCalls = $registryDefinition->getMethodCalls();
        $this->assertCount(1, $methodCalls);
        list($method, $args) = $methodCalls[0];
        $this->assertEquals('addFactory', $method);
        $this->assertCount(2, $args);
        $this->assertEquals('foobar', $args[0]);
        $this->assertEquals(new Reference('cmf_resource.repository_factory.test'), $args[1]);
    }
}

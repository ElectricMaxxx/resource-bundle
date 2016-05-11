<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\ResourceBundle\Tests\Unit\DependencyInjection;

use Symfony\Cmf\Bundle\ResourceBundle\DependencyInjection\CmfResourceExtension;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractExtensionTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class CmfResourceExtensionTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should set the repository configuration.
     */
    public function testExtension()
    {
        $container = new ContainerBuilder();
        $extension = new CmfResourceExtension();
        $extension->load([
            [
                'repositories' => [
                    'test' => [
                        'type' => 'foobar'
                    ]
                ]
            ]
        ], $container);

        $this->assertEquals([
            'test' => [
                'type' => 'foobar',
                'options' => [],
            ],
        ], $container->getParameter('cmf_resource.repository_configuration'));
    }
}

<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\ResourceBundle\Tests\Unit\Factory;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Doctrine\Bundle\PHPCRBundle\ManagerRegistry;
use Symfony\Cmf\Bundle\ResourceBundle\Factory\PhpcrOdmFactory;

class PhpcrOdmFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    public function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->registry = $this->prophesize(ManagerRegistry::class);
        $this->factory = new PhpcrOdmFactory(
            $this->container->reveal()
        );
    }

    /**
     * It should create a new repository instance.
     */
    public function testCreate()
    {
        $this->container->get('doctrine_phpcr')->willReturn(
            $this->registry->Reveal()
        );
        $this->factory->create(
            $this->factory->getDefaultConfig()
        );
    }
}

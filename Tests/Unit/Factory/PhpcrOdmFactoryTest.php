<?php

namespace Symfony\Cmf\Bundle\ResourceBundle\Tests\Unit\Factory;

use Symfony\Component\DependencyInjection\ContainerInterface;
use PHPCR\SessionInterface;
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

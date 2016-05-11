<?php

namespace Symfony\Cmf\Bundle\ResourceBundle\Tests\Unit\Factory;

use Symfony\Component\DependencyInjection\ContainerInterface;
use PHPCR\SessionInterface;
use Symfony\Cmf\Bundle\ResourceBundle\Factory\PhpcrFactory;

class PhpcrFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $container;

    public function setUp()
    {
        $this->container = $this->prophesize(ContainerInterface::class);
        $this->session = $this->prophesize(SessionInterface::class);
        $this->factory = new PhpcrFactory(
            $this->container->reveal()
        );
    }

    /**
     * It should create a new repository instance.
     */
    public function testCreate()
    {
        $this->container->get('doctrine_phpcr.session')->willReturn(
            $this->session->reveal()
        );
        $this->factory->create(
            $this->factory->getDefaultConfig()
        );
    }
}

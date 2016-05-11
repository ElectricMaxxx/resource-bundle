<?php

namespace Symfony\Cmf\Bundle\ResourceBundle\Tests\Unit\Registry;

use Symfony\Cmf\Component\Resource\RepositoryFactoryInterface;
use Puli\Repository\Api\ResourceRepository;
use Symfony\Cmf\Bundle\ResourceBundle\Registry\RepositoryRegistry;

class RepositoryRegistryTest extends \PHPUnit_Framework_TestCase
{
    private $repository;

    public function setUp()
    {
        $this->factory1 = $this->prophesize(RepositoryFactoryInterface::class);
        $this->repository = $this->prophesize(ResourceRepository::class);
    }

    public function createRegistry(array $factories, array $configuration)
    {
        $registry = new RepositoryRegistry(
            $configuration,
            'default'
        );

        foreach ($factories as $name => $factory) {
            $registry->addFactory($name, $factory);
        }

        return $registry;
    }

    /**
     * It should get a configured instance.
     */
    public function testGetInstanceName()
    {
        $registry = $this->createRegistry(
            [
                'doctrine/orm' => $this->factory1->reveal(),
            ],
            [
                'instance1' => [
                    'type' => 'doctrine/orm',
                    'options' => [],
                ],
            ]
        );

        $this->factory1->getDefaultConfig()->willReturn([]);
        $this->factory1->create([])->willReturn($this->repository->reveal());

        $repository = $registry->get('instance1');
        $this->assertSame($this->repository->reveal(), $repository);
    }

    /**
     * It should use a factories default options.
     */
    public function testFactoryDefaultOptions()
    {
        $registry = $this->createRegistry(
            [
                'doctrine/orm' => $this->factory1->reveal(),
            ],
            [
                'instance1' => [
                    'type' => 'doctrine/orm',
                    'options' => [
                        'hello' => 'foobar',
                    ],
                ],
            ]
        );

        $this->factory1->getDefaultConfig()->willReturn([
            'hello' => 'world',
            'goodbye' => 'cruel world',
        ]);
        $this->factory1->create([
            'hello' => 'foobar',
            'goodbye' => 'cruel world',
        ])->willReturn($this->repository->reveal());

        $repository = $registry->get('instance1');
        $this->assertSame($this->repository->reveal(), $repository);
    }

    /**
     * It should throw an exception if an invalid key is given.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Invalid configuration keys "unknown_option" for repository type "doctrine/orm", valid config keys: "goodbye"
     */
    public function testInvalidKey()
    {
        $registry = $this->createRegistry(
            [
                'doctrine/orm' => $this->factory1->reveal(),
            ],
            [
                'instance1' => [
                    'type' => 'doctrine/orm',
'options' => [
                    'unknown_option' => null,
                ],
                ],
            ]
        );

        $this->factory1->getDefaultConfig()->willReturn([
            'goodbye' => 'cruel world',
        ]);

        $registry->get('instance1');
    }

    /**
     * It should throw an exception if the repository instance does not exist.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Repository instance "foobar" has not been registered
     */
    public function testInstanceNotExist()
    {
        $registry = $this->createRegistry([], []);
        $registry->get('foobar');
    }

    /**
     * It should throw an exception if the repository instance configuration does not have the "type" key.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Each instance configuration must have a "type" key (for configuration "instance1")
     */
    public function testNoTypeKey()
    {
        $registry = $this->createRegistry(
            [
                'doctrine/orm' => $this->factory1->reveal(),
            ],
            [
                'instance1' => [
                ],
            ]
        );

        $registry->get('instance1');
    }

    /**
     * It should throw an exception if the repository type does not exist.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Unknown repository type "not_exist", known types: "doctrine/orm"
     */
    public function testTypeNotExist()
    {
        $registry = $this->createRegistry(
            [
                'doctrine/orm' => $this->factory1->reveal(),
            ],
            [
                'instance1' => [
                    'type' => 'not_exist',
'options' => [],
                ],
            ]
        );

        $registry->get('instance1');
    }

    /**
     * It should return the alias of a given repository instance.
     */
    public function testGetRepositoryAlias()
    {
        $registry = $this->createRegistry(
            [
                'doctrine/orm' => $this->factory1->reveal(),
            ],
            [
                'instance1' => [
                    'type' => 'doctrine/orm',
'options' => [],
                ],
            ]
        );

        $this->factory1->getDefaultConfig()->willReturn([]);
        $this->factory1->create([])->willReturn($this->repository->reveal());

        $repository = $registry->get('instance1');
        $alias = $registry->getRepositoryAlias($repository);
        $this->assertEquals('instance1', $alias);
    }

    /**
     * It should return the type of a given repository instance.
     */
    public function testGetRepositoryType()
    {
        $registry = $this->createRegistry(
            [
                'doctrine/orm' => $this->factory1->reveal(),
            ],
            [
                'instance1' => [
                    'type' => 'doctrine/orm',
'options' => [],
                ],
            ]
        );

        $this->factory1->getDefaultConfig()->willReturn([]);
        $this->factory1->create([])->willReturn($this->repository->reveal());

        $repository = $registry->get('instance1');
        $alias = $registry->getRepositoryType($repository);
        $this->assertEquals('doctrine/orm', $alias);
    }

    /**
     * It should throw an exception if the repository type cannot be resolved.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage No repository has been instantiated of class
     */
    public function testGetTypeUnknownRepository()
    {
        $repository = $this->prophesize(ResourceRepository::class);
        $registry = $this->createRegistry([], []);
        $registry->getRepositoryType($repository->reveal());
    }

    /**
     * It should throw an exception if the repository alias cannot be resolved.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Could not determine alias of repository of class
     */
    public function testGetAliasUnknownRepository()
    {
        $repository = $this->prophesize(ResourceRepository::class);
        $registry = $this->createRegistry([], []);
        $registry->getRepositoryAlias($repository->reveal());
    }
}

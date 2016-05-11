<?php

namespace Symfony\Cmf\Bundle\ResourceBundle\Tests\Unit\Factory;

use Symfony\Cmf\Component\Resource\RepositoryRegistryInterface;
use Symfony\Cmf\Bundle\ResourceBundle\Factory\CompositeFactory;
use Puli\Repository\Api\ResourceRepository;
use Puli\Repository\Api\Resource\Resource;

class CompositeFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $registry;
    private $factory;

    public function setUp()
    {
        $this->registry = $this->prophesize(RepositoryRegistryInterface::class);
        $this->factory = new CompositeFactory(
            $this->registry->reveal()
        );
        $this->repository = $this->prophesize(ResourceRepository::class);
        $this->resource = $this->prophesize(Resource::class);
    }

    /**
     * It should create a composite repository for the configured mount points.
     */
    public function testCreate()
    {
        $this->registry->get('repo1')->willReturn($this->repository->reveal());
        $this->repository->get('/foo')->willReturn($this->resource->reveal());
        $this->resource->createReference('/path/foo')->willReturn($this->resource->reveal());

        $composite = $this->factory->create([
            'mounts' => [
                [
                    'repository' => 'repo1',
                    'mountpoint' => '/path',
                ],
            ],
        ]);
        $resource = $composite->get('/path/foo');
        $this->assertSame($this->resource->reveal(), $resource);
    }

    /**
     * It should throw an exception if no mountpoint is specified.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You must specify the `mountpoint`
     */
    public function testMountpointRequired()
    {
        $this->factory->create([
            'mounts' => [
                [
                    'repository' => 'repo1',
                ],
            ],
        ]);
    }

    /**
     * It should throw an exception if no repository is specified.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage You must specify the `mountpoint`
     */
    public function testRepositoryRequired()
    {
        $this->factory->create([
            'mounts' => [
                [
                ],
            ],
        ]);
    }
}

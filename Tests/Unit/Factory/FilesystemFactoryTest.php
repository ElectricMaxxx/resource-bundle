<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\ResourceBundle\Tests\Unit;

use Symfony\Cmf\Bundle\ResourceBundle\Factory\FilesystemFactory;

class FilesystemFactoryTest extends \PHPUnit_Framework_TestCase
{
    private $factory;

    public function setUp()
    {
        $this->factory = new FilesystemFactory();
    }

    /**
     * It should create a new repository instance.
     */
    public function testCreateNewInstance()
    {
        $this->factory->create(
            array_merge(
                $this->factory->getDefaultConfig(),
                ['base_dir' => __DIR__]
            )
        );
    }

    /**
     * It should throw an exception if the basedir is null.
     *
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage requires the `base_dir` option
     */
    public function testCreateNewInstanceNoBaseDir()
    {
        $this->factory->create(
            array_merge(
                $this->factory->getDefaultConfig(),
                ['base_dir' => null]
            )
        );
    }
}

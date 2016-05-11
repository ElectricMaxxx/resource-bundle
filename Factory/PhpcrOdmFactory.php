<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2015 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\ResourceBundle\Factory;

use Symfony\Cmf\Component\Resource\RepositoryFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Cmf\Component\Resource\Repository\PhpcrOdmRepository;

class PhpcrOdmFactory implements RepositoryFactoryInterface
{
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function create(array $options)
    {
        return new PhpcrOdmRepository(
            $this->container->get('doctrine_phpcr'),
            $options['basepath']
        );
    }

    public function getDefaultConfig()
    {
        return [
            'basepath' => null,
        ];
    }
}

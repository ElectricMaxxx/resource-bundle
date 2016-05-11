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
use Symfony\Cmf\Component\Resource\Repository\PhpcrRepository;

class PhpcrFactory implements RepositoryFactoryInterface
{
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options)
    {
        return new PhpcrRepository(
            $this->container->get('doctrine_phpcr.session'),
            $options['basedir']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultConfig()
    {
        return [
            'basedir' => null,
        ];
    }
}

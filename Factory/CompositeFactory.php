<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\ResourceBundle\Factory;

use Symfony\Cmf\Component\Resource\RepositoryFactoryInterface;
use Symfony\Cmf\Component\Resource\RepositoryRegistryInterface;
use Puli\Repository\CompositeRepository;

class CompositeFactory implements RepositoryFactoryInterface
{
    private $registry;

    /**
     * @param RepositoryRegistryInterface $registry
     */
    public function setRepositoryRegistry(RepositoryRegistryInterface $registry)
    {
        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function create(array $options)
    {
        $compositeRepoistory = new CompositeRepository();

        if (null === $this->registry) {
            throw new \RuntimeException(
                'The repository registry instance has not been set on the CompositeFactory.'
            );
        }

        foreach ($options['mounts'] as $mount) {
            if (!isset($mount['mountpoint'])) {
                throw new \InvalidArgumentException(sprintf(
                    'You must specify the `mountpoint` for a composite repository mount point.'
                ));
            }

            if (!isset($mount['repository'])) {
                throw new \InvalidArgumentException(sprintf(
                    'No `repository` specified for composite repository mount'
                ));
            }

            $compositeRepoistory->mount(
                $mount['mountpoint'],
                $this->registry->get($mount['repository'])
            );
        }

        return $compositeRepoistory;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultConfig()
    {
        return [
            'mounts' => [],
        ];
    }
}

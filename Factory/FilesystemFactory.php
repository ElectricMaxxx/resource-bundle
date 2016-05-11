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
use Puli\Repository\FilesystemRepository;

class FilesystemFactory implements RepositoryFactoryInterface
{
    /**
     * {@inheritdoc}
     */
    public function create(array $options)
    {
        if (null === $options['base_dir']) {
            throw new \InvalidArgumentException(
                'The filesystem repository type requires the `base_dir` option to be set.'
            );
        }

        return new FilesystemRepository(
            $options['base_dir'],
            $options['symlink']
        );
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultConfig()
    {
        return [
            'base_dir' => null,
            'symlink' => true,
        ];
    }
}

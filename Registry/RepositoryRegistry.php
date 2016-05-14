<?php

/*
 * This file is part of the Symfony CMF package.
 *
 * (c) 2011-2016 Symfony CMF
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Cmf\Bundle\ResourceBundle\Registry;

use Symfony\Cmf\Component\Resource\RepositoryFactoryInterface;
use Symfony\Cmf\Component\Resource\RepositoryRegistryInterface;
use Puli\Repository\Api\ResourceRepository;

/**
 * Repository registry which uses pre-registered factories to create
 * the repository instances according to the configuration.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class RepositoryRegistry implements RepositoryRegistryInterface
{
    private $factories = [];
    private $instances = [];
    private $typeMap = [];

    /**
     * @param array $factories
     * @param array $configurations
     */
    public function __construct(array $factories, array $configurations)
    {
        $this->factories = $factories;

        foreach ($configurations as $repositoryName => $config) {
            $this->createRepositoryInstance($repositoryName, $config);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($instanceName)
    {
        if (!isset($this->instances[$instanceName])) {
            throw new \InvalidArgumentException(sprintf(
                'Repository instance "%s" has not been registered, available repository instances: "%s"',
                $instanceName,
                implode('", "', array_keys($this->instances))
            ));
        }

        return $this->instances[$instanceName];
    }

    /**
     * {@inheritdoc}
     */
    public function getRepositoryAlias(ResourceRepository $repository)
    {
        foreach ($this->instances as $alias => $repositoryInstance) {
            if ($repositoryInstance === $repository) {
                return $alias;
            }
        }

        throw new \InvalidArgumentException(sprintf(
            'Unknown repository instance of type "%s", cannot determine the alias.',
            get_class($repository)
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getRepositoryType(ResourceRepository $repository)
    {
        $repositoryClass = get_class($repository);
        if (!isset($this->typeMap[$repositoryClass])) {
            throw new \InvalidArgumentException(sprintf(
                'No repository has been registered of class "%s", cannot determine the type, known types: "%s".',
                $repositoryClass,
                implode('", "', $this->typeMap)
            ));
        }

        return $this->typeMap[$repositoryClass];
    }

    private function createRepositoryInstance($instanceName, array $config)
    {
        if (!isset($config['type'])) {
            throw new \InvalidArgumentException(sprintf(
                'Each instance configuration must have a "type" key (for configuration "%s")',
                $instanceName
            ));
        }

        $type = $config['type'];
        $config = $config['options'];

        if (!isset($this->factories[$type])) {
            throw new \InvalidArgumentException(sprintf(
                'Unknown repository type "%s", known types: "%s"',
                $type, implode('", "', array_keys($this->factories))
            ));
        }

        $factory = $this->factories[$type];
        $defaultConfig = $factory->getDefaultConfig();

        $configDiff = array_diff(
            array_keys($config), 
            array_keys($defaultConfig)
        );


        if ($configDiff) {
            throw new \InvalidArgumentException(sprintf(
                'Invalid configuration keys "%s" for repository type "%s", valid config keys: "%s"',
                implode('", "', $configDiff),
                $type,
                implode('", "', array_keys($defaultConfig))
            ));
        }

        $config = array_merge($defaultConfig, $config);

        $repository = $factory->create($config);
        $this->typeMap[get_class($repository)] = $type;

        $this->instances[$instanceName] = $repository;
    }
}

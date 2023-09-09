<?php

namespace Common\Container;

use Config\Config\Config;
use Config\Config\ConfigPath;
use Exception;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

class Container implements IContainer
{
    private const CONTAINER_CONFIG_PARAMETER = ['container'];

    private Config $config;
    private array $createdObjects = [];
    private array $containerConfig;

    public function __construct(Config $config)
    {
        $this->config = $config;
        $this->containerConfig = $config->getParameter(new ConfigPath(self::CONTAINER_CONFIG_PARAMETER));
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function get(string $className): mixed
    {
        if (array_key_exists($className, $this->createdObjects)) {
            return $this->createdObjects[$className];
        }

        $config = $this->containerConfig[$className];
        $configuredClassName = $className;

        if (is_string($config)) {
            $configuredClassName = $config;
        }

        if (is_array($config)) {
            if (false === array_key_exists('class', $config)) {
                throw new ContainerException('Параметр class не задан');
            }

            $configuredClassName = $config['class'];
        }

        $reflectionClass = new ReflectionClass($configuredClassName);

        if (is_array($config)) {
            $parameters = $config['parameters'] ?? [];
            $dependencies = $this->getConfigDependencies($parameters, $className);
        } else {
            $constructor = $reflectionClass->getConstructor();

            $dependencies = [];
            if ($constructor) {
                $dependencies = $this->getDependencies($reflectionClass, $constructor);
            }
        }

        $instance = $reflectionClass->newInstance(...$dependencies);
        $this->cacheInstance($className, $instance);

        return $instance;
    }

    public function has(string $classname): bool
    {
        return class_exists($classname);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    private function getDependencies(ReflectionClass $reflectionClass, ReflectionMethod $constructor): array
    {
        $dependencies = [];
        foreach ($constructor->getParameters() as $parameter) {
            if (null === $parameter->getType()) {
                throw new ContainerException(
                    'Not defined constructor parameter type. Class: "' . $reflectionClass->getName() . '" ' .
                    'Parameter: "' . $parameter->getName() . '".'
                );
            }

            $parameterType = $parameter->getType()->getName();
            $dependencies[] = $this->get($parameterType);
        }

        return $dependencies;
    }

    private function cacheInstance(string $className, object $instance): void
    {
        $this->createdObjects[$className] = $instance;
    }

    private function getConfigDependencies(array $parameters, string $className): array
    {
        $dependencies = [];
        foreach ($parameters as $parameter) {
            if (is_array($parameter)) {
                if (false === array_key_exists('class', $parameter)) {
                    throw new ContainerException('Не задан класс параметра объекта "' . $className . '". Свойство "class" не задано.');
                }

                $dependencies[] = $this->get($parameter['class']);
            }

            if (is_callable($parameter)) {
                $dependencies[] = $parameter($this->config, $this);
            }

            if (is_string($parameter)) {
                $dependencies[] = $parameter;
            }
        }

        return $dependencies;
    }
}
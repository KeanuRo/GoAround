<?php

namespace Common\Container;

use Config\Config\Config;
use Config\Config\ConfigPath;
use ReflectionException;

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
     */
    public function get(string $className): mixed
    {
        if (array_key_exists($className, $this->createdObjects)) {
            return $this->createdObjects[$className];
        }

        $config = $this->containerConfig[$className] ?? $className;

        if (is_string($config)) {
            $configObject = ObjectConfig::createFromString($config);
        } else {
            $configObject = ObjectConfig::createFromArray($config);
        }

        $objectPrototype = new ObjectPrototype($configObject);
        $parameterPrototypes = $objectPrototype->getParameterPrototypes();
        $objectParameters = $this->resolveParameterPrototypes($parameterPrototypes);
        $instance = $objectPrototype->createInstance($objectParameters);

        if ($config->needCaching()) {
            $this->cacheInstance($className, $instance);
        }

        return $instance;
    }

    public function has(string $classname): bool
    {
        return class_exists($classname);
    }

    private function cacheInstance(string $className, object $instance): void
    {
        $this->createdObjects[$className] = $instance;
    }

    /**
     * @param ObjectParameterPrototype[] $parameterPrototypes
     * @return array
     * @throws ReflectionException
     */
    private function resolveParameterPrototypes(array $parameterPrototypes): array
    {
        $resolvedParameters = [];
        foreach ($parameterPrototypes as $parameterPrototype) {
            if ($parameterPrototype->isClass()) {
                $resolvedParameters[] = $this->get($parameterPrototype->value);
            }

            if ($parameterPrototype->isCallable()) {
                $resolvedParameters[] = ($parameterPrototype->value)($this->config, $this);
            }

            if ($parameterPrototype->isScalar()) {
                $resolvedParameters[] = $parameterPrototype->value;
            }
        }

        return $resolvedParameters;
    }
}
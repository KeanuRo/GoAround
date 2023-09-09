<?php

namespace Common\Container;

use ReflectionClass;
use ReflectionException;

class ObjectPrototype
{
    private ObjectConfig $config;
    private ReflectionClass $reflectionClass;

    /**
     * @param ObjectConfig $config
     * @throws ReflectionException
     */
    public function __construct(ObjectConfig $config)
    {
        $this->config = $config;
        $this->reflectionClass = new ReflectionClass($config->className);
    }

    public function getParameterPrototypes(): array
    {
        $parameters = $this->getConfigParameterPrototypes() + $this->getConstructorParameterPrototypes();

        return array_filter($parameters);
    }

    private function getConfigParameterPrototypes(): array
    {
        $parameters = [];
        foreach ($this->config->parameters as $parameter) {
            $parameters[] = new ObjectParameterPrototype($parameter);
        }

        return $parameters;
    }

    private function getConstructorParameterPrototypes(): array
    {
        $constructorParameters = [];

        $constructor = $this->reflectionClass->getConstructor();
        if (null === $constructor) {
            return $constructorParameters;
        }

        foreach ($constructor->getParameters() as $constructorParameter) {
            $parameterType = $constructorParameter->getType();
            if (null === $parameterType) {
                $constructorParameters[] = null;
            }

            if ($parameterType->isBuiltin()) {
                $constructorParameters[] = null;
            }

            $constructorParameters[] = new ObjectParameterPrototype($parameterType->getName());
        }

        return $constructorParameters;
    }

    /**
     * @throws ReflectionException
     */
    public function createInstance(array $constructorParameters): mixed
    {
        return $this->reflectionClass->newInstance(...$constructorParameters);
    }
}
<?php

namespace Common\Container;

class ObjectConfig
{
    private const OBJECT_CLASS_PARAMETER = 'class';
    private const OBJECT_PARAMETERS_PARAMETER = 'parameters';
    private const OBJECT_CACHING_PARAMETER = 'caching';

    private array $config;

    public readonly string $className;
    public readonly array $parameters;

    private function __construct(array $config)
    {
        $this->config = $config;
        $this->className = $this->config[self::OBJECT_CLASS_PARAMETER];
        $this->parameters = $this->config[self::OBJECT_PARAMETERS_PARAMETER] ?? [];
    }

    public static function createFromString(string $class): self
    {
        return self::createFromArray([
            self::OBJECT_CLASS_PARAMETER => $class,
        ]);
    }

    public static function createFromArray(array $config): self
    {
        if (false === array_key_exists('class', $config)) {
            throw new ContainerException('Параметр "' . self::OBJECT_CLASS_PARAMETER . '" не задан');
        }

        return new self($config);
    }

    public function needCaching(): bool
    {
        return $this->config[self::OBJECT_CACHING_PARAMETER] ?? true;
    }
}
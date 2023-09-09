<?php

namespace Common\Container;

class ObjectParameterPrototype
{
    private const OBJECT_CLASS_PARAMETER = 'class';

    private bool $isClass = false;

    public readonly mixed $value;

    public function __construct(mixed $value)
    {
        if (is_array($value)) {
            if (false === array_key_exists(self::OBJECT_CLASS_PARAMETER, $value)) {
                throw new ContainerException(
                    'Не задан класс параметра объекта. Свойство "' . self::OBJECT_CLASS_PARAMETER . '" не задано.'
                );
            }

            $value = $value[self::OBJECT_CLASS_PARAMETER];
            $this->isClass = true;
        }

        $this->value = $value;
    }

    public function isClass(): bool
    {
        return $this->isClass;
    }

    public function isScalar(): bool
    {
        return (
            false === $this->isClass
            && (
                is_scalar($this->value)
                ||
                is_array($this->value)
            )
        );
    }

    public function isCallable(): bool
    {
        return is_callable($this->value);
    }
}
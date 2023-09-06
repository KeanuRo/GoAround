<?php

namespace Common\Container;

interface IContainer
{
    public function get(string $className): mixed;
    public function has(string $classname): bool;
}
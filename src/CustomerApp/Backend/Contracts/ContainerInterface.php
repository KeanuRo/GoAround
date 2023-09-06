<?php

namespace GoAroundCustomer\Contracts;

interface ContainerInterface
{
    public function get(string $className): mixed;
    public function has(string $classname): bool;
}
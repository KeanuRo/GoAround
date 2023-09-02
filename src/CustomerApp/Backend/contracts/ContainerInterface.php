<?php

namespace GoAroundCustomer\contracts;

interface ContainerInterface
{
    public function get(string $className): mixed;
    public function has(string $classname): bool;
}
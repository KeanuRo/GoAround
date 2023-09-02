<?php

namespace GoAroundCustomer\contracts;

interface ContainerInterface
{
    public function get(string $classname);
    public function has(string $classname);
}
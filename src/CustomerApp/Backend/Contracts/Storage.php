<?php

interface Storage
{
    public function set(string $name, mixed $value): void;

    public function get(string $name): mixed;

    public function slice(string $name): mixed;
}
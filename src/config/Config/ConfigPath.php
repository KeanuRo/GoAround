<?php

namespace Config\Config;

use Iterator;

class ConfigPath implements Iterator
{
    /**
     * @var string[]
     */
    private array $path;

    /**
     * @param string[] $path
     */
    public function __construct(array $path)
    {
        $this->path = $path;
    }

    public function current(): string
    {
        return current($this->path);
    }

    public function next(): void
    {
        next($this->path);
    }

    public function key(): string|int|null
    {
        return key($this->path);
    }

    public function valid(): bool
    {
        return (null !== key($this->path));
    }

    public function rewind(): void
    {
        reset($this->path);
    }
}
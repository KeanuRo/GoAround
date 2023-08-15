<?php

namespace Config\DotEnvLoader;

use Loader\DotEnvLoader;

class KeanuDotEnvLoaderAdapter implements IDotEnvLoader
{
    private DotEnvLoader $keanuLoader;

    public function __construct()
    {
        $this->keanuLoader = new DotEnvLoader();
    }

    public function load(string $dotEnvPath): void
    {
        $this->keanuLoader->load($dotEnvPath);
    }
}
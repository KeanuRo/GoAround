<?php

namespace Config\DotEnvLoader;

interface IDotEnvLoader
{
    public function load(string $dotEnvPath): void;
}
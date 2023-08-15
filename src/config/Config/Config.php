<?php

namespace Config\Config;

use Config\DotEnvLoader\IDotEnvLoader;

class Config
{
    private const DOT_ENV_PATH_PARAMETER = 'dotEnvPath';

    private array $config = [];

    public function __construct(IDotEnvLoader $dotEnvLoader)
    {
        $this->loadConfig();

        $loader = $dotEnvLoader;
        $loader->load($this->config[self::DOT_ENV_PATH_PARAMETER]);
    }

    private function loadConfig(): void
    {
        $configParts = [];
        foreach (glob(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . '*.php') as $configFile) {
            $configPart = include $configFile;
            $configParts[] = $configPart;
        }

        $this->config = array_merge(...$configParts);
    }
}
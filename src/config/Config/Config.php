<?php

namespace Config\Config;

use Config\Config\ConfigPath;
use Config\DotEnvLoader\IDotEnvLoader;

class Config
{
    private const DOT_ENV_PATH_PARAMETER = 'dotEnvPath';

    private array $config = [];

    public function __construct(IDotEnvLoader $dotEnvLoader)
    {
        $this->loadEnvConfig();
        $dotEnvPath = $this->getParameter(new ConfigPath(['env', 'dotEnvPath']));
        $dotEnvLoader->load($dotEnvPath);

        $this->loadConfig();
    }

    private function loadConfig(): void
    {
        $configParts = [];
        foreach (glob($this->getConfigPath() . '*.php') as $configFile) {
            $configPart = include $configFile;
            $configParts[] = $configPart;
        }

        $this->config = array_merge($this->config, ...$configParts);
    }

    public function getParameter(ConfigPath $path): mixed
    {
        $configSection = &$this->config;
        foreach ($path as $pathPart) {
            $configSection = $configSection[$pathPart];
        }

        return $configSection;
    }

    public function setParameter(ConfigPath $path, mixed $value): void
    {
        $configSection = &$this->config;
        foreach ($path as $pathPart) {
            if (false === array_key_exists($pathPart, $configSection)){
                $configSection[$pathPart] = [];
            }

            $configSection = &$configSection[$pathPart];
        }

        $configSection = $value;
    }

    private function getConfigPath(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
    }

    private function loadEnvConfig(): void
    {
        $envConfig = include $this->getConfigPath() . 'env.php';
        $this->config = array_merge($this->config, $envConfig);
    }


}
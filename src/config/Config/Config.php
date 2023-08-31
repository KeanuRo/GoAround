<?php

namespace Config\Config;

use Config\DotEnvLoader\IDotEnvLoader;

class Config
{
    private const ENV_CONFIG_FILE_NAME = 'env.php';
    private const DOT_ENV_PATH_PARAMETER = ['env', 'dotEnvPath'];

    private array $config = [];

    public function __construct(IDotEnvLoader $dotEnvLoader)
    {
        $this->loadEnvConfig();
        $dotEnvPath = $this->getParameter(new ConfigPath(self::DOT_ENV_PATH_PARAMETER));
        $dotEnvLoader->load($dotEnvPath);

        $this->loadConfig();
    }

    public static function getEnv(string $paramName): mixed
    {
        $value = getenv($paramName);

        if (false === $value) {
            throw new ConfigException('Environment parameter "' . $paramName . '" not found');
        }

        $booleanValue = filter_var($value, FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);

        return $booleanValue ?? $value;
    }

    public function getParameter(ConfigPath $path): mixed
    {
        $configSection = &$this->config;
        foreach ($path as $pathPart) {
            $configSection = &$configSection[$pathPart];
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

    private function loadConfig(): void
    {
        $configParts = [];
        foreach (glob($this->getConfigPath() . '*.php') as $configFile) {
            $configPart = include $configFile;
            $configParts[] = $configPart;
        }

        $this->config = array_merge($this->config, ...$configParts);
    }

    private function getConfigPath(): string
    {
        return __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR;
    }

    private function loadEnvConfig(): void
    {
        $envConfig = include $this->getConfigPath() . self::ENV_CONFIG_FILE_NAME;
        $this->config = array_merge($this->config, $envConfig);
    }


}
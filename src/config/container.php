<?php

return [
    'container' => [
        \Config\DotEnvLoader\IDotEnvLoader::class => \Config\DotEnvLoader\KeanuDotEnvLoaderAdapter::class,
        \Config\Config\Config::class => [
            'class' => \Config\Config\Config::class,
            'caching' => false,
            'parameters' => [
                ['class' => \Config\DotEnvLoader\IDotEnvLoader::class],
                'configPath',
                function(\Config\Config\Config $config, \Common\Container\IContainer $container) {
                    return new SplQueue($container->get(\Config\DotEnvLoader\IDotEnvLoader::class));
                }
            ],
        ],
    ],
];

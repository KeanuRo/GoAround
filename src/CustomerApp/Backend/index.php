<?php

require '../../../vendor/autoload.php';

include_once('contracts/controller.php');
include_once('contracts/model.php');
include_once('contracts/storage.php');
include_once('Controllers/shop.php');
include_once('Models/articles.php');
include_once('utils/Logger.php');
include_once('contracts/ContainerInterface.php');

use GoAroundCustomer\contracts\ContainerInterface;
use GoAroundCustomer\Controllers\shop;
use GoAroundCustomer\Models\articles;
use GoAroundCustomer\utils\session;
use GoAroundCustomer\Controllers\home;

class Container implements ContainerInterface
{
    private array $createdObjects = [];
    private array $config;

    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    public function get(string $className): mixed
    {
        if (array_key_exists($className, $this->createdObjects)) {
            return $this->createdObjects[$className];
        }

        $configuredClassName = $this->config[$className] ?? $className;
        $reflectionClass = new ReflectionClass($configuredClassName);
        $constructor = $reflectionClass->getConstructor();

        $dependencies = [];
        if ($constructor) {
            $dependencies = $this->getDependencies($reflectionClass, $constructor);
        }

        $instance = $reflectionClass->newInstance(...$dependencies);
        $this->cacheInstance($className, $instance);

        return $instance;
    }

    public function has(string $classname): bool
    {
        return class_exists($classname);
    }

    /**
     * @throws ReflectionException
     * @throws Exception
     */
    private function getDependencies(ReflectionClass $reflectionClass, ReflectionMethod $constructor): array
    {
        $dependencies = [];
        foreach ($constructor->getParameters() as $parameter) {
            if (null === $parameter->getType()) {
                throw new Exception(
                    'Not defined constructor parameter type. Class: "' . $reflectionClass->getName() . '" ' .
                    'Parameter: "' . $parameter->getName() . '".'
                );
            }

            $parameterType = $parameter->getType()->getName();
            $dependencies[] = $this->get($parameterType);
        }

        return $dependencies;
    }

    private function cacheInstance(string $className, object $instance): void
    {
        $this->createdObjects[$className] = $instance;
    }
}

$container = new Container([
    Model::class => articles::class,
    Storage::class => session::class,
]);

$controller = $container->get(shop::class);
$controller->run();

//$Articles = new articles();
//$session = new session();
//$controller = new shop($Articles, $session);
//$controller->run();
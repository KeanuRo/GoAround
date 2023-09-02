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
    protected array $binds;
    protected mixed $entries;

    public function bind(string $type, string $subtype)
    {
        $this->binds[$type] = $subtype;
    }

    public function get(string $classname)
    {
        $ref = new ReflectionClass($classname);
        $contr = $ref->getConstructor();
        $deps = [];
        if ($contr !== null) {
            $attrs = $contr->getParameters();
            foreach ($attrs as $attr) {
                $name = $attr->getType()->getName();
                if (isset($this->binds[$name])) {
                    $name = $this->binds[$name];
                }
                $deps[] = $this->get($name);
            }
        }
        return new $classname(...$deps);
    }

    public function has(string $classname)
    {
        try {
            $ref = new ReflectionClass($classname);
            return true;
        } catch (Exception) {
            return false;
        }
    }
}

$container = new Container();
$container->bind(Model::class, articles::class);
$container->bind(Storage::class, session::class);
$controller = $container->get(shop::class);
var_dump($controller);
//$Articles = new articles();
//$session = new session();
//$controller = new shop($Articles, $session);
//$controller->run();
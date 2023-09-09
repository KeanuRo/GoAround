<?php

error_reporting(E_ALL);

use Config\Config\ConfigPath;
use Config\Config\Config;
use Config\DotEnvLoader\KeanuDotEnvLoaderAdapter;
use Common\Container\Container;

define('ROOT_DIR', dirname(dirname(__DIR__) . '/..'));

require ROOT_DIR . '/vendor/autoload.php';

$config = new Config(new KeanuDotEnvLoaderAdapter());

$path = new ConfigPath(['databases', 'newDB', 'password']);

$config->setParameter($path, 'newPass');

$container = new Container($config);

?>

<pre><?php var_dump($container->get(Config::class))?></pre>

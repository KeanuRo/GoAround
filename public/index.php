<?php

use Config\Config\Config;
use Config\DotEnvLoader\KeanuDotEnvLoaderAdapter;

define('ROOT_DIR', dirname(dirname(__DIR__) . '/..'));

require ROOT_DIR . '/vendor/autoload.php';

$config = new Config(new KeanuDotEnvLoaderAdapter());

$config->set();

var_dump($config->get());

?>

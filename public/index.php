<?php

error_reporting(E_ALL);

use Config\Config\ConfigPath;
use Config\Config\Config;
use Config\DotEnvLoader\KeanuDotEnvLoaderAdapter;

define('ROOT_DIR', dirname(dirname(__DIR__) . '/..'));

require ROOT_DIR . '/vendor/autoload.php';

$config = new Config(new KeanuDotEnvLoaderAdapter());

$path = new ConfigPath(['databases', 'newDB', 'password']);

$config->setParameter($path, 'newPass');

?>

<pre><?php var_dump($config)?></pre>

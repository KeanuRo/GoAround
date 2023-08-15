<?php

use Config\Config\Config;
use Config\DotEnvLoader\KeanuDotEnvLoaderAdapter;

require __DIR__ . '/../vendor/autoload.php';

$config = new Config(new KeanuDotEnvLoaderAdapter());

?>

<pre><?php print_r($_SERVER) ?></pre>

<?php

use Helpers\DotEnvLoader;

require __DIR__ . '/../vendor/autoload.php';

$dotEnv = new DotEnvLoader();

var_dump($dotEnv->read());

//$db = pg_connect( "$host $port $dbname $credentials"  );
//
//var_dump($db);
//if(!$db) {
//    echo "Error : Unable to open database\n";
//} else {
//    echo "Opened database successfully\n";
//}
?>
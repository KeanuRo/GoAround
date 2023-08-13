<?php

use GoAroundCustomer\DotenvLoader;

require __DIR__ . '/../vendor/autoload.php';

function var_dump_fixed ($data) {
    echo "<pre>";
    var_dump($data);
    echo "</pre>";
}


$envLoader = new DotenvLoader('../.env');

$envLoader->readDotenv('var_dump_fixed');


//$db = pg_connect( "$host $port $dbname $credentials"  );
//
//var_dump($db);
//if(!$db) {
//    echo "Error : Unable to open database\n";
//} else {
//    echo "Opened database successfully\n";
//}
?>
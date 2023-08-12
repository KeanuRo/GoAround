<?php
$host        = "host = postgres";
$port        = "port = 5432";
$dbname      = "dbname = my_db";
$credentials = "user = root password=root";

use GoAroundCustomer\Test;

var_dump(new Test());

//$db = pg_connect( "$host $port $dbname $credentials"  );
//
//var_dump($db);
//if(!$db) {
//    echo "Error : Unable to open database\n";
//} else {
//    echo "Opened database successfully\n";
//}
?>
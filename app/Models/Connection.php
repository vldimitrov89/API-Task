<?php

use Illuminate\Database\Capsule\Manager as Capsule;
use Api\app\Models\Database as Database;

$capsule = new Capsule();

$database = new Database();
$database->load();

//connect to database
$capsule->addConnection([
    'driver' => $database->get('mysql')['driver'],
    'host' => $database->get('mysql')['host'],
    'database' => $database->get('mysql')['database'],
    'username' => $database->get('mysql')['username'],
    'password' => $database->get('mysql')['password'],
    'charset' => $database->get('mysql')['charset'],
    'collation' => $database->get('mysql')['collation'],
    'prefix' => $database->get('mysql')['prefix'],
]);

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

<?php

use Pecee\SimpleRouter\SimpleRouter;

const ROOT_PATH = __DIR__;

// Composer autoload
include ROOT_PATH . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(ROOT_PATH);
$dotenv->safeLoad();

$app = new Api\bootstrap\App();
$app->load();

// Start the routing
SimpleRouter::start();

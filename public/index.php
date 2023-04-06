<?php

session_start();

define('ROOT', dirname(__DIR__));

require_once(ROOT . '/vendor/autoload.php');

require_once(ROOT . '/src/Config/Autoloader.php');

spl_autoload_register([Autoloader::class, 'loadClass']);

use Manager\RouterManager;

$router = new RouterManager();
$router->routing();

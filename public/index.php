<?php

session_start();

define('ROOT', dirname(__DIR__));

require_once(ROOT . '/vendor/autoload.php');

require_once(ROOT . '/lib/Autoloader.php');

spl_autoload_register([Autoloader::class, 'loadClass']);

use Manager\RooterManager;

$rooter = new RooterManager();
$rooter->rooting();

<?php

session_start();

require_once(dirname(__DIR__) . '/lib/Autoloader.php');
spl_autoload_register([Autoloader::class, 'loadClass']);

use Manager\RooterManager;

$rooter = new RooterManager();
$rooter->rooting();

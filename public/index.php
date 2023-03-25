<?php

session_start();

define('ROOT', dirname(__DIR__));

require_once(ROOT . '/lib/Autoloader.php');
require_once(ROOT . '/vendor/autoload.php');


// Dev/Prod configuration
const LOCAL_ENVIRONMENT = ROOT . '/src/config/local.php';
const PROD_ENVIRONMENT = ROOT . '/src/config/production.php';

match (true){
    file_exists(LOCAL_ENVIRONMENT) => require_once(LOCAL_ENVIRONMENT),
    default => require_once(PROD_ENVIRONMENT)
};

spl_autoload_register([Autoloader::class, 'loadClass']);

use Manager\RooterManager;

$rooter = new RooterManager();
$rooter->rooting();

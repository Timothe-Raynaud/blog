<?php

define('ROOT', dirname(__DIR__));

require_once ROOT . '/config/autoloader.php';
require_once ROOT . '/vendor/autoload.php';

// Dev/Prod configuration
const DEV_ENVIRONMENT = 'DEV';

match (DEV_ENVIRONMENT){
    'DEV' => include '_dev.php',
    'PROD' => include '_prod.php'
};

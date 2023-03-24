<?php

define('ROOT', dirname(__DIR__));

require_once ROOT . '/config/autoloader.php';
require_once ROOT . '/vendor/autoload.php';

// Dev/Prod configuration
const DEV_ENVIRONMENT = ROOT . '/config/_dev.php';
const PROD_ENVIRONMENT = ROOT . '/config/_prod.php';

match (file_exists(DEV_ENVIRONMENT)){
    true => include DEV_ENVIRONMENT,
    default => include PROD_ENVIRONMENT
};

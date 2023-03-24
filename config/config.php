<?php

define('ROOT', dirname(__DIR__));

require_once ROOT . '/config/autoloader.php';
require_once ROOT . '/vendor/autoload.php';

// Dev/Prod configuration
const LOCAL_ENVIRONMENT = ROOT . '/config/local.php';
const PROD_ENVIRONMENT = ROOT . '/config/production.php';

match (file_exists(LOCAL_ENVIRONMENT)){
true => include LOCAL_ENVIRONMENT,
default => include PROD_ENVIRONMENT
};

<?php

define('ROOT', dirname(__DIR__));

require_once(ROOT . '/vendor/autoload.php');

// Dev/Prod configuration
const LOCAL_ENVIRONMENT = ROOT . '/config/local.php';
const PROD_ENVIRONMENT = ROOT . '/config/production.php';

match (true){
file_exists(LOCAL_ENVIRONMENT) => require_once(LOCAL_ENVIRONMENT),
default => require_once(PROD_ENVIRONMENT)
};


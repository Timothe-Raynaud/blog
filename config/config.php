<?php

const DEV_ENVIRONMENT = true;
const TO = 'timothe@timotheraynaud.com';

require_once ROOT . '/config/autoloader.php';
require_once ROOT . '/vendor/autoload.php';
require_once ROOT . '/config/database.php';


if (DEV_ENVIRONMENT) {
    ini_set('display_startup_errors', 'on');
    ini_set('display_errors', 'on');
    ini_set('html_errors', 'on');
    ini_set('log_errors', 'off');
    ini_set('error_log', '');
} else {
    ini_set('display_startup_errors', 'off');
    ini_set('display_errors', 'off');
    ini_set('html_errors', 'off');
    ini_set('log_errors', 'on');
    ini_set('error_log', '../PHP_errors.log');
}
<?php

// Error Configuration
ini_set('display_startup_errors', 'off');
ini_set('display_errors', 'off');
ini_set('html_errors', 'off');
ini_set('log_errors', 'on');
ini_set('error_log', '../PHP_errors.log');

// Mail Configuration
define("MAIL_TO", 'timothe@timotheraynaud.com');
define("MAIL_FROM", 'contact@timotheraynaud.com');
define("MAIL_HOST", '');
define("MAIL_PORT", '');
define("MAIL_SMTP_AUTH", '');
define("MAIL_SMTP_SECURE", '');

// Database configuration
define("DB_HOST", '');
define("DB_NAME", '');
define("DB_USER", '');
define("DB_PASSWORD", '');
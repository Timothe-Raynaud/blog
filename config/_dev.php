<?php

// Mail Configuration
define("MAIL_TO", 'test@test.com');
define("MAIL_FROM", 'contact@test.com');
define("MAIL_HOST", 'mailhog');
define("MAIL_PORT", 1025);
define("MAIL_SMTP_AUTH", false);
define("MAIL_SMTP_SECURE", '');

// Database Configuration
define("DB_HOST", 'local_dev_8-db');
define("DB_NAME", 'my_blog');
define("DB_USER", 'root');
define("DB_PASSWORD", 'root');

// Error Configuration
ini_set('display_startup_errors', 'on');
ini_set('display_errors', 'on');
ini_set('html_errors', 'on');
ini_set('log_errors', 'off');
ini_set('error_log', '');

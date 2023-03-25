<?php

namespace Config;

class Config
{
    // ROOT
    public static string $ROOT = __DIR__ . '/../..';

    // Mail Configuration
    public static string $MAIL_TO = 'test@test.com';
    public static string $MAIL_FROM = 'contact@test.com';
    public static string $MAIL_HOST = 'mailhog';
    public static int $MAIL_PORT = 1025;
    public static bool $MAIL_SMTP_AUTH = false;
    public static string $MAIL_SMTP_SECURE = '';

    // Database Configuration
    public static string $DB_HOST = 'local_dev_8-db';
    public static string $DB_NAME = 'my_blog';
    public static string $DB_USER = 'root';
    public static string $DB_PASSWORD = 'root';

}

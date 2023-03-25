<?php

namespace Manager;

use PDO;
use Config\Config;


class DatabaseConnection
{
    private PDO $database;

    public function __construct()
    {
        $this->database = $this->getConnection();
    }

    public function getConnection() : PDO
    {
        return new PDO("mysql:host=" . Config::$DB_HOST . ";dbname=" . Config::$DB_NAME, Config::$DB_USER, Config::$DB_PASSWORD);
    }

    public function pdo() : PDO
    {
        return $this->database;
    }
}

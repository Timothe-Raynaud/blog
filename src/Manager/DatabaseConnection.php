<?php

namespace Manager;

use PDO;

class DatabaseConnection
{
    private PDO $database;

    public function __construct()
    {
        $this->database = $this->getConnection();
    }

    public function getConnection() : PDO
    {
        return new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
    }

    public function pdo() : PDO
    {
        return $this->database;
    }
}

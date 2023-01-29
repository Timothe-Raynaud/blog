<?php

namespace Manager;

require_once ROOT.'config/config.php';

use PDO;
use Exception;

class DatabaseConnection
{
    private PDO $database;

    public function __construct()
    {
        $this->database = $this->getConnection();
    }

    public function getConnection() : PDO
    {
        $pdo = null;
        try{
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD);
        } catch (Exception $exception){
            if(DEV_ENVIRONMENT){
                die('Error : ' .$exception->getMessage());
            }
        }

        return $pdo;
    }

    public function pdo() : PDO
    {
        return $this->database;
    }
}



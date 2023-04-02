<?php

namespace Repository;

use Manager\DatabaseConnection;

class RolesRepository
{
    private DatabaseConnection $database;

    public function __construct()
    {
        $this->database = new DatabaseConnection();
    }

    public function getRoles(): array
    {
        $sql = '
            SELECT *
            FROM roles
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

}

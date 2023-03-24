<?php

namespace Repository;

use Manager;

class RolesRepository
{
    private Manager\DatabaseConnection $database;

    public function __construct()
    {
        $this->database = new Manager\DatabaseConnection();
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

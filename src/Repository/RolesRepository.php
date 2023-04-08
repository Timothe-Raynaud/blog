<?php

namespace Repository;

use Entity\Role;
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

        $resultRequest = $statement->fetchAll();

        $roles = [];
        foreach ($resultRequest as $result){
            $role = new Role(
                $result['role_id'],
                $result['role'],
            );
            $roles[] = $role;
        }

        return $roles;
    }

    public function getRoleById(int $id): Role
    {
        $sql = '
            SELECT *
            FROM roles
            WHERE role_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        $result = $statement->fetch();

        return new Role(
            $result['role_id'],
            $result['role'],
        );
    }

}

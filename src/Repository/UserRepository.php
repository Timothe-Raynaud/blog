<?php

namespace Repository;

require_once ROOT.'/config/config.php';

use Manager;

class UserRepository
{
    private Manager\DatabaseConnection $database;

    public function __construct()
    {
        $this->database = new Manager\DatabaseConnection();
    }

    public function getAllUsers() : ?array
    {
        $sql = '
            SELECT * 
            FROM users
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getUserByLogin($login) : mixed
    {
        $sql = '
            SELECT * 
            FROM users 
            WHERE login = :login
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':login', $login);
        $statement->execute();

        return $statement->fetch();
    }

    public function getUserByPasswordAndLogin($password, $login) : ?array
    {
        $sql = '
            SELECT * 
            FROM users 
            WHERE login = :login
            AND password = :password
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':login', $login);
        $statement->bindValue(':password', $password);
        $statement->execute();

        return $statement->fetch();
    }

    public function getUserById($id) : ?array
    {
        $sql = '
            SELECT * 
            FROM users 
            WHERE id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        return $statement->fetch();
    }

    public function setPassword($id, $password) : void
    {
        $sql = '
            UPDATE users 
            SET :password 
            WHERE id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->bindValue(':password', $password);
        $statement->execute();
    }

    public function setUser($login, $password, $role, $contactId ) : void
    {
        $sql = '
            INSERT INTO users (login, password, role, contact_id) 
            VALUES (:login, :password, :role, :role, :contactId )
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':login', $login);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':role', $role);
        $statement->bindValue(':contactId', $contactId);
        $statement->execute();
    }


    public function deleteUser($id) : void
    {
        $sql = '
            DELETE FROM users 
            WHERE user_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}
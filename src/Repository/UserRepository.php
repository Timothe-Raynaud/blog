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
            FROM users u
            INNER JOIN contacts c ON c.contact_id = u.contact_id
            WHERE u.login = :login
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':login', $login);
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
            SET password = :password 
            WHERE id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->bindValue(':password', $password);
        $statement->execute();
    }

    public function setUser($login, $password, $role, $contactId ) : bool
    {
        try {
            $sql = '
                INSERT INTO users (login, password, role, contact_id) 
                VALUES (:login, :password,  :role, :contactId )
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':login', $login);
            $statement->bindValue(':password', $password);
            $statement->bindValue(':role', $role);
            $statement->bindValue(':contactId', $contactId);
            $statement->execute();

            return true;
        } catch (Exception $exception){
            if (DEV_ENVIRONMENT){
                var_dump($exception);
            }
            return false;
        }
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
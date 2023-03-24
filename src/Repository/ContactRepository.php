<?php

namespace Repository;

use Manager;
use Exception;

class ContactRepository
{
    private Manager\DatabaseConnection $database;

    public function __construct()
    {
        $this->database = new Manager\DatabaseConnection();
    }

    public function getContacts() : ?array
    {
        $sql = '
            SELECT * 
            FROM contacts
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getContactsById($id) : ?array
    {
        $sql = '
            SELECT * 
            FROM contacts 
            WHERE contact_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        return $statement->fetch();
    }

    public function getContactsByUsername($username) : mixed
    {
        $sql = '
            SELECT * 
            FROM contacts 
            WHERE username = :username
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':username', $username);
        $statement->execute();

        return $statement->fetch();
    }

    public function getContactsByEmail($email) : mixed
    {
        $sql = '
            SELECT * 
            FROM contacts 
            WHERE email = :email
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':email', $email);
        $statement->execute();

        return $statement->fetch();
    }

    public function setContact($username, $email ) : bool
    {
        try {
            $sql = '
                INSERT INTO contacts (username, email) 
                VALUES (:username, :email)
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':username', $username);
            $statement->bindValue(':email', $email);
            $statement->execute();

            return true;

        } catch (Exception $exception){
            var_dump($exception);
        }

        return false;
    }

    public function updateContact($id, $username, $email) : bool
    {
        try {
            $sql = '
                UPDATE contacts
                SET username = :username
                    , email = :email
                WHERE contact_id = :id
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':username', $username);
            $statement->bindValue(':email', $email);
            $statement->bindValue(':id', $id);
            $statement->execute();

            return true;

        } catch (Exception $exception){
            var_dump($exception);
        }

        return false;
    }

    public function deleteContacts($id) : void
    {
        $sql = '
            DELETE FROM contacts 
            WHERE user_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}
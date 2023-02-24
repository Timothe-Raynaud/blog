<?php

namespace Repository;

require_once ROOT.'/config/config.php';

use Manager;

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

    public function setContact($username, $email ) : void
    {
        $sql = '
            INSERT INTO contacts (username, email) 
            VALUES (:username, :email)
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':email', $email);
        $statement->execute();
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
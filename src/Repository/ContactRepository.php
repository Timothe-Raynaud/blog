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

    public function getContacts() : array
    {
        $sql = '
            SELECT * 
            FROM contacts
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getContactsById($id) : array
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

    public function setContact($firstname, $lastname, $email ) : void
    {
        $sql = '
            INSERT INTO contacts (firstname, lastname, email) 
            VALUES (:firstname, :lastname, :email)
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':firstname', $firstname);
        $statement->bindValue(':lastname', $lastname);
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
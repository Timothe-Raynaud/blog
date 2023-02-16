<?php

namespace Repository;

require_once ROOT.'config/config.php';

use Manager;

class ContactRepository
{
    private Manager\DatabaseConnection $database;

    public function __construct()
    {
        $this->database = new Manager\DatabaseConnection();
    }

    public function getContactsName($id)
    {
        $statement = $this->database->pdo()->prepare('SELECT contact_id FROM contacts WHERE contact_id');
        $statement->bindValue(':id', $id);

        $statement->execute();

        return $statement->fetch();
    }

    public function getAllContacts() : array
    {
        $statement = $this->database->pdo()->prepare('SELECT * FROM contacts');
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getContactsById($id)
    {
        $statement = $this->database->pdo()->prepare('SELECT * FROM contacts WHERE contact_id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();

        return $statement->fetch();
    }

    public function createContacts($login, $password, $mail, $firstname, $lastname )
    {
        $statement = $this->database->pdo()->prepare('INSERT INTO contacts (login, password, mail, role, fistname, lastname) VALUES (:login, :password, :mail, subscriber, :firstname, :lastname )');
        $statement->bindValue(':login', $login);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':mail', $mail);
        $statement->bindValue(':firstname', $firstname);
        $statement->bindValue(':lastname', $lastname);
        $statement->execute();
    }


    public function deleteContacts($id)
    {
        $statement = $this->database->pdo()->prepare('DELETE FROM contacts WHERE user_id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}
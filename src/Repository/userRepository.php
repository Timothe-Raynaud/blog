<?php

namespace Repository;

require_once ROOT.'config/config.php';

use Manager;

class userRepository
{
    private Manager\DatabaseConnection $database;

    public function __construct()
    {
        $this->database = new Manager\DatabaseConnection();
    }

    /**
     * @return mixed
     */
    public function getUsername($id)
    {
        $statement = $this->database->pdo()->prepare('SELECT id FROM users WHERE id');
        $statement->bindValue(':id', $id);

        $statement->execute();
        return username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username): void
    {
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email): void
    {
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password): void
    {
    }

    /**
     * @return mixed
     */
    public function getRoles()
    {
        return roles;
    }

    /**
     * @param mixed $roles
     */
    public function setRoles($roles): void
    {
    }

    /**
     * @return mixed
     */
    public function getFirstname()
    {
    }

    /**
     * @param mixed $firstname
     */
    public function setFirstname($firstname): void
    {
    }

    /**
     * @return mixed
     */
    public function getLastname()
    {
        return lastname;
    }

    /**
     * @param mixed $lastname
     */
    public function setLastname($lastname): void
    {
    }


    public function getAllUsers() : array
    {
        $statement = $this->database->pdo()->prepare('SELECT * FROM users');
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getUserById($id)
    {
        $statement = $this->database->pdo->prepare('SELECT * FROM users WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();

        return $statement->fetch();
    }

    public function createUser($login, $password, $mail, $firstname, $lastname )
    {
        $statement = $this->database->pdo->prepare('INSERT INTO users (login, password, mail, role, fistname, lastname) VALUES (:login, :password, :mail, subscriber, :firstname, :lastname )');
        $statement->bindValue(':login', $login);
        $statement->bindValue(':password', $password);
        $statement->bindValue(':mail', $mail);
        $statement->bindValue(':firstname', $firstname);
        $statement->bindValue(':lastname', $lastname);
        $statement->execute();
    }


    public function deleteUser($id)
    {
        $statement = $this->database->pdo->prepare('DELETE FROM users WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}
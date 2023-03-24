<?php

namespace Repository;

use Manager;
use Exception;

class UserRepository
{
    private Manager\DatabaseConnection $database;

    public function __construct()
    {
        $this->database = new Manager\DatabaseConnection();
    }

    public function getAllUsers(): ?array
    {
        $sql = '
            SELECT * 
            FROM users u
            INNER JOIN contacts c ON c.contact_id = u.contact_id
            INNER JOIN roles r ON r.role_id = u.role_id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getAvailableUsers(): ?array
    {
        $sql = '
            SELECT COUNT(*) as numberOf
            FROM users u
            WHERE u.is_available = 1
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetch();
    }
    public function getUserByLogin(string $login): mixed
    {
        $sql = '
            SELECT * 
            FROM users u
            INNER JOIN contacts c ON c.contact_id = u.contact_id
            INNER JOIN roles r ON r.role_id = u.role_id
            WHERE u.login = :login
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':login', $login);
        $statement->execute();

        return $statement->fetch();
    }

    public function getUserByEmail(string $email): mixed
    {
        $sql = '
            SELECT * 
            FROM users u
            INNER JOIN contacts c ON c.contact_id = u.contact_id
            INNER JOIN roles r ON r.role_id = u.role_id
            WHERE c.email = :email
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':email', $email);
        $statement->execute();

        return $statement->fetch();
    }

    public function getUserById(int $id): ?array
    {
        $sql = '
            SELECT * 
            FROM users u
            INNER JOIN contacts c ON c.contact_id = u.contact_id 
            INNER JOIN roles r ON r.role_id = u.role_id
            WHERE user_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * @throws Exception
     */
    public function setUser(string $login, string $password, int $contactId): bool
    {
        try {
            $sql = '
                INSERT INTO users (login, password, role_id, contact_id, is_available) 
                VALUES (:login, :password,  1, :contactId, 1)
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':login', $login);
            $statement->bindValue(':password', $password);
            $statement->bindValue(':contactId', $contactId);
            $statement->execute();

            return true;
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
        return false;
    }

    public function setIsAvailable(int $userId): void
    {
        $sql = '
            UPDATE users
            SET is_available = 1
            WHERE user_id = :userId
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':userId', $userId);
        $statement->execute();
    }

    public function setIsNotAvailable(int $userId): void
    {
        $sql = '
            UPDATE users
            SET is_available = 0
            WHERE user_id = :userId
            AND role_id != 3
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':userId', $userId);
        $statement->execute();
    }

    /**
     * @throws Exception
     */
    public function updatePassword(int $id, string $password): bool
    {
        try{
            $sql = '
                UPDATE users 
                SET password = :password 
                WHERE user_id = :id
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':password', $password);
            $statement->execute();

            return true;

        } catch (Exception $exception){
            throw new Exception($exception);
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function updateAccount(int $id, string $login): bool
    {
        try{
            $sql = '
                UPDATE users 
                SET login = :login
                WHERE user_id = :id
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':login', $login);
            $statement->execute();

            return true;

        } catch (Exception $exception){
            throw new Exception($exception);
        }

        return false;
    }

    /**
     * @throws Exception
     */
    public function updateRole(int $user_id, int $role): bool
    {
        try{
            $sql = '
                UPDATE users 
                SET role_id = :role
                WHERE user_id = :id
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':id', $user_id);
            $statement->bindValue(':role', $role);
            $statement->execute();

            return true;

        } catch (Exception $exception){
            throw new Exception($exception);
        }

        return false;
    }

    public function deleteUser(int $id): void
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

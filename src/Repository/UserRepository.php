<?php

namespace Repository;

use Entity\User;
use Exception;
use Manager\DatabaseConnection;

class UserRepository
{
    private DatabaseConnection $database;
    private RolesRepository $rolesRepository;

    public function __construct()
    {
        $this->rolesRepository = new RolesRepository();
        $this->database = new DatabaseConnection();
    }

    public function getAllUsers(): ?array
    {
        $sql = '
            SELECT * 
            FROM users u
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        $resultRequest = $statement->fetchAll();

        $users = [];
        foreach ($resultRequest as $result){
            $user = new User(
                $result['user_id'],
                $result['login'],
                $result['password'],
                $this->rolesRepository->getRoleById($result['role_id']),
                $result['username'],
                $result['email'],
                $result['is_available']
            );
            $users[] = $user;
        }

        return $users;
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

    public function getUserByLogin(string $login): ?User
    {
        $sql = '
            SELECT * 
            FROM users u
            WHERE u.login = :login
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':login', $login);
        $statement->execute();

        $result = $statement->fetch();
        $user = null;

        if ($result){
            $user = new User(
                $result['user_id'],
                $result['login'],
                $result['password'],
                $this->rolesRepository->getRoleById($result['role_id']),
                $result['username'],
                $result['email'],
                $result['is_available']
            );
        }

        return $user;
    }

    public function getUserByEmail(string $email): ?User
    {
        $sql = '
            SELECT * 
            FROM users u
            WHERE u.email = :email
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':email', $email);
        $statement->execute();

        $result = $statement->fetch();
        $user = null;

        if ($result){
            $user = new User(
                $result['user_id'],
                $result['login'],
                $result['password'],
                $this->rolesRepository->getRoleById($result['role_id']),
                $result['username'],
                $result['email'],
                $result['is_available']
            );
        }

        return $user;
    }

    public function getUserById(?int $id): ?User
    {
        if ($id === null){
            return null;
        }
        $sql = '
            SELECT * 
            FROM users u
            WHERE user_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        $result = $statement->fetch();
        $user = null;

        if ($result){
            $user = new User(
                $result['user_id'],
                $result['login'],
                $result['password'],
                $this->rolesRepository->getRoleById($result['role_id']),
                $result['username'],
                $result['email'],
                $result['is_available']
            );
        }

        return $user;
    }

    public function getUserByUsername(string $username): ?User
    {
        $sql = '
            SELECT * 
            FROM users u
            INNER JOIN roles r ON r.role_id = u.role_id
            WHERE username = :username
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':username', $username);
        $statement->execute();

        $result = $statement->fetch();
        $user = null;

        if ($result){
            $user = new User(
                $result['user_id'],
                $result['login'],
                $result['password'],
                $this->rolesRepository->getRoleById($result['role_id']),
                $result['username'],
                $result['email'],
                $result['is_available']
            );
        }

        return $user;
    }

    /**
     * @throws Exception
     */
    public function setUser(string $login, string $password, string $username, string $email): bool
    {
        try {
            $sql = '
                INSERT INTO users (login, password, role_id, username, email, is_available) 
                VALUES (:login, :password,  1, :username, :email, 1)
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':login', $login);
            $statement->bindValue(':password', $password);
            $statement->bindValue(':username', $username);
            $statement->bindValue(':email', $email);
            $statement->execute();

            return true;
        } catch (Exception $exception) {
            throw new Exception($exception);
        }
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
    }

    /**
     * @throws Exception
     */
    public function updateAccount(int $id, string $login, $username, $email): bool
    {
        try{
            $sql = '
                UPDATE users 
                SET login = :login
                    , username = :username
                    , email = :email
                WHERE user_id = :id
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':login', $login);
            $statement->bindValue(':username', $username);
            $statement->bindValue(':email', $email);
            $statement->execute();

            return true;

        } catch (Exception $exception){
            throw new Exception($exception);
        }
    }

    /**
     * @throws Exception
     */
    public function updateUsername(int $id, string $username): bool
    {
        try{
            $sql = '
                UPDATE users 
                SET username = :username
                WHERE user_id = :id
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':username', $username);
            $statement->execute();

            return true;

        } catch (Exception $exception){
            throw new Exception($exception);
        }
    }

    /**
     * @throws Exception
     */
    public function updateEmail(int $id, string $email): bool
    {
        try{
            $sql = '
                UPDATE users 
                SET email = :email
                WHERE user_id = :id
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':email', $email);
            $statement->execute();

            return true;

        } catch (Exception $exception){
            throw new Exception($exception);
        }
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

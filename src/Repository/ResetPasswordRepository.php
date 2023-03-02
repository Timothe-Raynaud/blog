<?php

namespace Repository;

require_once ROOT . '/config/config.php';

use DateInterval;
use DateTime;
use Manager;
use Exception;

class ResetPasswordRepository
{
    private Manager\DatabaseConnection $database;

    public function __construct()
    {
        $this->database = new Manager\DatabaseConnection();
    }

    public function getResetPassword(): ?array
    {
        $sql = '
            SELECT * 
            FROM reset_password
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getResetPasswordById($id): ?array
    {
        $sql = '
            SELECT * 
            FROM reset_password 
            WHERE reset_password_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        return $statement->fetch();
    }


    public function getResetPasswordByToken($token): mixed
    {
        $sql = '
            SELECT * 
            FROM reset_password 
            WHERE token = :token
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':token', $token);
        $statement->execute();

        return $statement->fetch();
    }

    public function setResetPassword($token, $userId): bool
    {
        $date = new DateTime();
        $date->add(new DateInterval('P1D'));
        $expirationDate = $date->format('Y-m-d H:i:s');

        try {
            $sql = '
                INSERT INTO reset_password (token, user_id, is_used, expiration_date) 
                VALUES (:token, :userId, 0, :expirationDate)
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':token', $token);
            $statement->bindValue(':userId', $userId);
            $statement->bindValue(':expirationDate', $expirationDate);
            $statement->execute();
            return true;
        } catch (Exception $exception) {
            if (DEV_ENVIRONMENT) {
                var_dump($exception);
            }
            return false;
        }
    }

}
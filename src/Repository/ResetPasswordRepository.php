<?php

namespace Repository;

use DateInterval;
use DateTime;
use Exception;
use Manager\DatabaseConnection;

class ResetPasswordRepository
{
    private DatabaseConnection $database;

    public function __construct()
    {
        $this->database = new DatabaseConnection();
    }

    public function getResetUserByToken(string $token): mixed
    {
        $sql = '
            SELECT * 
            FROM reset_password rp 
            INNER JOIN users u ON u.user_id = rp.user_id
            WHERE token = :token
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':token', $token);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * @throws Exception
     */
    public function setResetPassword(string $token, int $userId): bool
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
            throw new Exception($exception);
        }
    }

    /**
     * @throws Exception
     */
    public function setIsUsed(string $token): bool
    {
        try {
            $sql = '
                UPDATE reset_password
                SET is_used = 1
                WHERE token = :token 
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':token', $token);
            $statement->execute();

            return true;

        } catch (Exception $exception) {
            throw new Exception($exception);
        }
    }

}

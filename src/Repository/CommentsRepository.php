<?php

namespace Repository;

use Exception;
use Manager\DatabaseConnection;

class CommentsRepository
{
    private DatabaseConnection $database;

    public function __construct()
    {
        $this->database = new DatabaseConnection();
    }

    public function getAllComments() : ?array
    {
        $sql = '
            SELECT com.*
                , p.title as postTitle
                , u.*
                , con.*
            FROM comments com
            INNER JOIN users u ON u.user_id = com.user_id
            INNER JOIN contacts con ON con.contact_id = u.contact_id
            INNER JOIN posts p ON p.post_id = com.post_id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getValidatedCommentByPostId(int $id) : ?array
    {
        $sql = '
            SELECT com.*
                , u.*
                , con.*
            FROM comments com
            INNER JOIN users u ON u.user_id = com.user_id
            INNER JOIN contacts con ON con.contact_id = u.contact_id
            WHERE post_id = :id
            AND is_validated = 1
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countValidatedCommentByPostId() : ?array
    {
        $sql = '
            SELECT COUNT(*) as validatedComments
            FROM comments com
            INNER JOIN users u ON u.user_id = com.user_id
            INNER JOIN contacts con ON con.contact_id = u.contact_id
            WHERE is_validated = 1
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetch();
    }

    public function setIsAvailable(int $commentId): void
    {
        $sql = '
            UPDATE comments
            SET is_validated = 1
            WHERE comment_id = :commentId
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':commentId', $commentId);
        $statement->execute();
    }

    public function setIsNotAvailable(int $commentId): void
    {
        $sql = '
            UPDATE comments
            SET is_validated = 0
            WHERE comment_id = :commentId
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':commentId', $commentId);
        $statement->execute();
    }

    /**
     * @throws Exception
     */
    public function setComment(int $postId, string $content, int $userId) : ?bool
    {
        try {
            $sql = '
                INSERT INTO comments (post_id, content, user_id, published_at, is_validated) 
                VALUES (:postId, :content, :userId, NOW(), 0)
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':postId', $postId);
            $statement->bindValue(':content', $content);
            $statement->bindValue(':userId', $userId);
            $statement->execute();

            return true;
        } catch (Exception $exception){
            throw new $exception;
        }
    }

    public function deleteComment(int $id) : void
    {
        $sql = '
            DELETE FROM comments 
            WHERE comment_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}

<?php

namespace Repository;

use Exception;
use Manager\DatabaseConnection;

class PostsRepository
{
    private DatabaseConnection $database;

    public function __construct()
    {
        $this->database = new DatabaseConnection();
    }

    public function getAllPosts() : ?array
    {
        $sql = '
            SELECT p.*
                , u.login as creator_login
                , c.username as creator_username
                , c.email as creator_email
                , up.login as updator_login
                , cp.username as updator_username
                , cp.email as updator_email
            FROM posts p
            INNER JOIN users u ON u.user_id = p.created_by
            INNER JOIN contacts c ON c.contact_id = u.contact_id
            LEFT JOIN users up ON up.user_id = p.updated_by
            LEFT JOIN contacts cp ON cp.contact_id = up.contact_id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getValidatedPosts() : ?array
    {
        $sql = '
            SELECT p.*
                , u.login as creator_login
                , c.username as creator_username
                , c.email as creator_email
                , up.login as updator_login
                , cp.username as updator_username
                , cp.email as updator_email
            FROM posts p
            INNER JOIN users u ON u.user_id = p.created_by
            INNER JOIN contacts c ON c.contact_id = u.contact_id
            LEFT JOIN users up ON up.user_id = p.updated_by
            LEFT JOIN contacts cp ON cp.contact_id = up.contact_id
            WHERE is_validated = 1
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getPostById(int $id) : ?array
    {
        $sql = '
            SELECT * 
            FROM posts p
            INNER JOIN users u ON u.user_id = p.created_by
            INNER JOIN contacts c ON c.contact_id = u.contact_id
            WHERE post_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        return $statement->fetch();
    }

    public function setIsAvailable(int $postId): void
    {
        $sql = '
            UPDATE posts
            SET is_validated = 1
            WHERE post_id = :postId
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':postId', $postId);
        $statement->execute();
    }

    public function countValidatedPosts(): array
    {
        $sql = '
            SELECT COUNT(*) as validatedPosts
            FROM posts
            WHERE is_validated = 1
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetch();
    }

    public function setIsNotAvailable(int $postId): void
    {
        $sql = '
            UPDATE posts
            SET is_validated = 0
            WHERE post_id = :postId
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':postId', $postId);
        $statement->execute();
    }

    /**
     * @throws Exception
     */
    public function addPost(string $title, string $chapo, string $content, int $userId) : ?bool
    {
        try {
            $sql = '
                INSERT INTO posts (title, chapo, content, created_by, published_at, is_validated) 
                VALUES (:title, :chapo, :content, :userId, NOW(), 0)
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':chapo', $chapo);
            $statement->bindValue(':content', $content);
            $statement->bindValue(':userId', $userId);
            $statement->execute();

            return true;
        } catch (Exception $exception){
            throw new $exception;
        }
    }

    /**
     * @throws Exception
     */
    public function updatePost(int $id, string $title, string $chapo, string $content, int $userId) : ?bool
    {
        try{
            $sql = '
                UPDATE posts 
                SET title = :title, chapo = :chapo, content = :content, updated_by = :userId ,updated_at = NOW()
                WHERE post_id = :id
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':id', $id);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':chapo', $chapo);
            $statement->bindValue(':content', $content);
            $statement->bindValue(':userId', $userId);
            $statement->execute();

            return true;
        } catch (Exception $exception){
            throw new $exception;
        }
    }

    public function deletePost(int $id) : void
    {
        $sql = '
            DELETE FROM posts 
            WHERE post_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}

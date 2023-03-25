<?php

namespace Repository;

use DateTime;
use Manager;
use Exception;

class PostsRepository
{
    private Manager\DatabaseConnection $database;

    public function __construct()
    {
        $this->database = new Manager\DatabaseConnection();
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
            INNER JOIN contacts c ON c.contact_id = p.created_by 
            WHERE post_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        return $statement->fetch();
    }

    /**
     * @throws Exception
     */
    public function addPost(string $title, string $content, int $userId) : ?bool
    {
        try {
            $sql = '
                INSERT INTO posts (title, content, created_by, published_at, is_validated) 
                VALUES (:title, :content, :userId, NOW(), 0)
            ';
            $statement = $this->database->pdo()->prepare($sql);
            $statement->bindValue(':title', $title);
            $statement->bindValue(':content', $content);
            $statement->bindValue(':userId', $userId);
            $statement->execute();

            return true;
        } catch (Exception $exception){
            throw new $exception;
        }
    }

    public function updatePost(int $id, string $title, string $subtitle, string $content, int $userId, DateTime $updatedAt) : void
    {
        $sql = '
            UPDATE posts 
            SET title = :title, subtitle = :subtitle, content = :content, updated_by = :userId ,updated_at = :updatedAt 
            WHERE post_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':subtitle', $subtitle);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':userId', $userId);
        $statement->bindValue(':updatedAt', $updatedAt);
        $statement->execute();
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

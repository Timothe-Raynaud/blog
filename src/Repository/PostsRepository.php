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
            SELECT * 
            FROM posts
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getValidatedPosts() : ?array
    {
        $sql = '
            SELECT * 
            FROM posts
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

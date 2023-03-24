<?php

namespace Repository;

use DateTime;
use Manager;

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

    public function addPost(string $title, string $content, int $userId, DateTime $publishedAt ) : void
    {
        $sql = '
            INSERT INTO posts (title, content, created_by, published_at, is_validated) 
            VALUES (:title, :content, :userId, :publishedAt, 0)
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':userId', $userId);
        $statement->bindValue(':publishedAt', $publishedAt);
        $statement->execute();
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
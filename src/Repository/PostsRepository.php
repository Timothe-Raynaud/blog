<?php

namespace Repository;

require_once ROOT.'/config/config.php';

use DateTime;
use Manager;

class PostsRepository
{
    private Manager\DatabaseConnection $database;

    public function __construct()
    {
        $this->database = new Manager\DatabaseConnection();
    }

    public function getAllPosts() : array
    {
        $sql = '
            SELECT * 
            FROM posts
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getPostById($id) : array
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

    public function addPost($title, $content, $contactId, DateTime $publishedAt ) : void
    {
        $sql = '
            INSERT INTO posts (title, content, created_by, published_at) 
            VALUES (:title, :content, :contactId, :publishedAt)
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':contactId', $contactId);
        $statement->bindValue(':publishedAt', $publishedAt);
        $statement->execute();
    }

    public function updatePost($id, $title, $subtitle, $content, DateTime $updatedAt) : void
    {
        $sql = '
            UPDATE posts 
            SET title = :title, subtitle = :subtitle, content = :content, updated_at = :updatedAt 
            WHERE post_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':subtitle', $subtitle);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':updatedAt', $updatedAt);
        $statement->execute();
    }

    public function deletePost($id) : void
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
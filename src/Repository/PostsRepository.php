<?php

namespace Repository;

require_once ROOT.'config/config.php';

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
        $statement = $this->database->pdo()->prepare('SELECT * FROM posts');
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getPostById($id)
    {
        $statement = $this->database->pdo()->prepare('SELECT * FROM posts WHERE post_id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();

        return $statement->fetch();
    }

    public function addPost($title, $content, $user, DateTime $publishedAt )
    {
        $statement = $this->database->pdo()->prepare('INSERT INTO posts (title, content, created_by, published_at) VALUES (:title, :content, :user, :publishedAt)');
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':user', $user);
        $statement->bindValue(':publishedAt', $publishedAt);
        $statement->execute();
    }

    public function updatePost($id, $title, $content, DateTime $updatedAt)
    {
        $statement = $this->database->pdo()->prepare('UPDATE posts SET title = :title, content = :content, updated_at = :updatedAt WHERE post_id = :id');
        $statement->bindValue(':id', $id);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':updatedAt', $updatedAt);
        $statement->execute();
    }

    public function deletePost($id)
    {
        $statement = $this->database->pdo()->prepare('DELETE FROM posts WHERE post_id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}
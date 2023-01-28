<?php

namespace Repository;

use PDO;
use DateTime;

class PostsRepository
{
    private $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function getAllPosts()
    {
        $statement = $this->pdo->prepare('SELECT * FROM posts');
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getPostById($id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM posts WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();

        return $statement->fetch();
    }

    public function addPost($title, $content, $user, DateTime $publishedAt )
    {
        $statement = $this->pdo->prepare('INSERT INTO posts (title, content, created_by, published_at) VALUES (:title, :content, :user, :publishedAt)');
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':user', $user);
        $statement->bindValue(':publishedAt', $publishedAt);
        $statement->execute();
    }

    public function updatePost($id, $title, $content, DateTime $updatedAt)
    {
        $statement = $this->pdo->prepare('UPDATE posts SET title = :title, content = :content, updated_at = :updatedAt WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->bindValue(':updatedAt', $updatedAt);
        $statement->execute();
    }

    public function deletePost($id)
    {
        $statement = $this->pdo->prepare('DELETE FROM posts WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}
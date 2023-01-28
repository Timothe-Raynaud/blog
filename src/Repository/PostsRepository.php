<?php

namespace App\Repository\PostsRepository;

use PDO;

class PostsRepository
{
    private $pdo;

    public function __construct()
    {
        $dbConfig = require_once '../../config/database.php';

        $this->pdo = new PDO(
            $dbConfig['driver'] . ':host=' . $dbConfig['host'] . ';dbname=' . $dbConfig['database'],
            $dbConfig['username'],
            $dbConfig['password']
        );
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

    public function addPost($title, $content)
    {
        $statement = $this->pdo->prepare('INSERT INTO posts (title, content) VALUES (:title, :content)');
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->execute();
    }

    public function updatePost($id, $title, $content)
    {
        $statement = $this->pdo->prepare('UPDATE posts SET title = :title, content = :content WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->bindValue(':title', $title);
        $statement->bindValue(':content', $content);
        $statement->execute();
    }

    public function deletePost($id)
    {
        $statement = $this->pdo->prepare('DELETE FROM posts WHERE id = :id');
        $statement->bindValue(':id', $id);
        $statement->execute();
    }
}
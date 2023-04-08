<?php

namespace Repository;

use DateTime;
use Entity\Post;
use Exception;
use Manager\DatabaseConnection;

class PostsRepository
{
    private DatabaseConnection $database;
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->database = new DatabaseConnection();
        $this->userRepository = new UserRepository();
    }

    /**
     * @throws Exception
     */
    public function getAllPosts() : ?array
    {
        $sql = '
            SELECT *
            FROM posts p
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        $resultRequest = $statement->fetchAll();

        $posts = [];
        foreach ($resultRequest as $result){
            $post = new Post(
                $result['post_id'],
                $result['title'],
                $result['chapo'],
                $result['content'],
                $this->userRepository->getUserById($result['created_by']),
                $this->userRepository->getUserById($result['updated_by']) ?? null,
                new DateTime($result['published_at']),
                isset($result['updated_at']) ? new DateTime($result['updated_at']) : null,
                $result['is_validated'],
            );

            $posts[] = $post;
        }

        return $posts;
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

        $resultRequest = $statement->fetchAll();

        $posts = [];
        foreach ($resultRequest as $result){
            $post = new Post(
                $result['post_id'],
                $result['title'],
                $result['chapo'],
                $result['content'],
                $this->userRepository->getUserById($result['created_by']),
                $this->userRepository->getUserById($result['updated_by']) ?? null,
                new DateTime($result['published_at']),
                isset($result['updated_at']) ? new DateTime($result['updated_at']) : null,
                $result['is_validated'],
            );

            $posts[] = $post;
        }

        return $posts;
    }

    /**
     * @throws Exception
     */
    public function getPostById(int $id) : ?Post
    {
        $sql = '
            SELECT * 
            FROM posts 
            WHERE post_id = :id
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        $result = $statement->fetch();

        return new Post(
            $result['post_id'],
            $result['title'],
            $result['chapo'],
            $result['content'],
            $this->userRepository->getUserById($result['created_by']),
            $this->userRepository->getUserById($result['updated_by']) ?? null,
            new DateTime($result['published_at']),
            isset($result['updated_at']) ? new DateTime($result['updated_at']) : null,
            $result['is_validated'],
        );
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

<?php

namespace Repository;

use DateTime;
use Exception;
use Entity\Comment;
use Manager\DatabaseConnection;

class CommentsRepository
{
    private DatabaseConnection $database;
    private PostsRepository $postsRepository;
    private UserRepository $usersRepository;

    public function __construct()
    {
        $this->usersRepository = new UserRepository();
        $this->postsRepository = new PostsRepository();
        $this->database = new DatabaseConnection();
    }

    public function getAllComments() : ?array
    {
        $sql = '
            SELECT *
            FROM comments 
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->execute();

        $resultRequest = $statement->fetchAll();

        $comments = [];
        foreach ($resultRequest as $result){
            $comment = new Comment(
                $result['comment_id'],
                $this->postsRepository->getPostById($result['post_id']),
                $this->usersRepository->getUserById($result['user_id']),
                $result['content'],
                new DateTime($result['published_at']),
                $result['is_validated'],
            );
            $comments[] = $comment;
        }

        return $comments;
    }

    public function getValidatedCommentByPostId(int $id) : ?array
    {
        $sql = '
            SELECT *
            FROM comments 
            WHERE post_id = :id
            AND is_validated = 1
        ';
        $statement = $this->database->pdo()->prepare($sql);
        $statement->bindValue(':id', $id);
        $statement->execute();

        $resultRequest = $statement->fetchAll();

        $comments = [];
        foreach ($resultRequest as $result){
            $comment = new Comment(
                $result['comment_id'],
                $this->postsRepository->getPostById($result['post_id']),
                $this->usersRepository->getUserById($result['user_id']),
                $result['content'],
                new DateTime($result['published_at']),
                $result['is_validated'],
            );
            $comments[] = $comment;
        }

        return $comments;
    }

    public function countValidatedCommentByPostId() : ?array
    {
        $sql = '
            SELECT COUNT(*) as validatedComments
            FROM comments com
            INNER JOIN users u ON u.user_id = com.user_id
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

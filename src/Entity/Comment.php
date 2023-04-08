<?php

namespace Entity;

use DateTime;

class Comment
{
    private int $id;
    private Post $post;
    private User $user ;
    private string $content;
    private DateTime $publishedAt;
    private bool $isValidated;

    public function __construct(int $id, Post $post, User $user, string $content, DateTime $publishedAt, bool $isValidated) {
        $this->id = $id;
        $this->post = $post;
        $this->user = $user;
        $this->content = $content;
        $this->publishedAt = $publishedAt;
        $this->isValidated = $isValidated;
    }

    public function getId() : int {
        return $this->id;
    }

    public function getPost() : Post {
        return $this->post;
    }

    public function getUser() : User {
        return $this->user;
    }

    public function getContent() : string {
        return $this->content;
    }

    public function getPublishedAt() : DateTime {
        return $this->publishedAt;
    }

    public function getIsValidated() : bool {
        return $this->isValidated;
    }

}

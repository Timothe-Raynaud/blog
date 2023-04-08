<?php

namespace Entity;

use DateTime;

class Post
{
    private int $id;
    private string $title;
    private string $chapo;
    private string $content;
    private User $createdBy;
    private ?User $updatedBy;
    private DateTime $publishedAt;
    private ?DateTime $updatedAt;
    private bool $isValidated;

    public function __construct(int $id, string $title, string $chapo, string $content, User $createdBy,
                                ?User $updatedBy, DateTime $publishedAt, ?DateTime $updatedAt, bool $isValidated) {
        $this->id = $id;
        $this->title = $title;
        $this->chapo = $chapo;
        $this->content = $content;
        $this->createdBy = $createdBy;
        $this->updatedBy = $updatedBy;
        $this->publishedAt = $publishedAt;
        $this->updatedAt = $updatedAt;
        $this->isValidated = $isValidated;
    }

    public function getId() : int {
        return $this->id;
    }

    public function getTitle() : string {
        return $this->title;
    }

    public function getChapo() : string {
        return $this->chapo;
    }

    public function getContent() : string {
        return $this->content;
    }

    public function getCreatedBy() : User {
        return $this->createdBy;
    }

    public function getUpdatedBy() : ?User {
        return $this->updatedBy;
    }

    public function getPublishedAt() : DateTime {
        return $this->publishedAt;
    }

    public function getUpdatedAt() : ?DateTime {
        return $this->updatedAt;
    }

    public function getIsValidated() : bool {
        return $this->isValidated;
    }

}

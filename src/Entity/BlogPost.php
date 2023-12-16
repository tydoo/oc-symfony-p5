<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;

class BlogPost {
    private ?int $id = null;
    private string $title;
    private string $post;
    private DateTimeImmutable $created_at;
    private ?DateTime $updated_at = null;
    private User $user;
    private Category $category;

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getTitle(): string {
        return $this->title;
    }

    public function setTitle(string $title): self {
        $this->title = $title;
        return $this;
    }

    public function getPost(): string {
        return $this->post;
    }

    public function setPost(string $post): self {
        $this->post = $post;
        return $this;
    }

    public function getCreated_at(): DateTimeImmutable {
        return $this->created_at;
    }

    public function setCreated_at(DateTimeImmutable $created_at): self {
        $this->created_at = $created_at;
        return $this;
    }

    public function getUpdated_at(): ?DateTime {
        return $this->updated_at;
    }

    public function setUpdated_at(?DateTime $updated_at): self {
        $this->updated_at = $updated_at;
        return $this;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function setUser(User $user): self {
        $this->user = $user;
        return $this;
    }

    public function getCategory(): Category {
        return $this->category;
    }

    public function setCategory(Category $category): self {
        $this->category = $category;
        return $this;
    }
}

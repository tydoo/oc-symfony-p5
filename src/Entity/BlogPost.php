<?php

namespace App\Entity;

use DateTime;
use DateTimeImmutable;
use Core\AbstractEntity;
use Core\Attribute\Entity;
use App\Repository\BlogPostRepository;

#[Entity(table: 'blog_post', repository: BlogPostRepository::class)]
class BlogPost extends AbstractEntity {
    private ?int $id = null;
    private string $title;
    private string $post;
    private DateTimeImmutable $createdAt;
    private DateTime $updatedAt;
    private User $user;
    private Category $category;

    public function __construct() {
        $this->setCreatedAt(new DateTimeImmutable());
        $this->setUpdatedAt(new DateTime());
    }

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

    public function getCreatedAt(): DateTimeImmutable {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): ?DateTime {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?DateTime $updatedAt): self {
        $this->updatedAt = $updatedAt;
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

<?php

namespace App\Entity;

use App\Repository\CommentRepository;
use DateTimeImmutable;
use Core\AbstractEntity;
use Core\Attribute\Entity;

#[Entity(table: 'comment', repository: CommentRepository::class)]
class Comment extends AbstractEntity {

    private ?int $id = null;
    private string $comment;
    private DateTimeImmutable $createdAt;
    private bool $validated = false;
    private User $user;

    public function __construct() {
        $this->setCreatedAt(new DateTimeImmutable());
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function setId(int $id): self {
        $this->id = $id;
        return $this;
    }

    public function getComment(): string {
        return $this->comment;
    }

    public function setComment(string $comment): self {
        $this->comment = $comment;
        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function isValidated(): bool {
        return $this->validated;
    }

    public function setValidated(bool $validated): self {
        $this->validated = $validated;
        return $this;
    }

    public function getUser(): User {
        return $this->user;
    }

    public function setUser(User $user): self {
        $this->user = $user;
        return $this;
    }
}

<?php

namespace App\Entity;

use DateTimeImmutable;
use Core\AbstractEntity;

class Comment extends AbstractEntity {

    private ?int $id = null;
    private string $comment;
    private DateTimeImmutable $created_at;
    private bool $validated = false;
    private User $user;

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
        return $this->created_at;
    }

    public function setCreatedAt(DateTimeImmutable $created_at): self {
        $this->created_at = $created_at;
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
